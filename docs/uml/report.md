# AlmaStreet UML Diagrams

This report provides a high-level overview of the core backend flows of AlmaStreet, described through PlantUML diagrams. It focuses only on system behaviour and data consistency; UI mockups are documented separately in docs/mockup/report.md.

---

## Registration & Email Verification
- **Source**: [docs/uml/eng/registration.wsd](docs/uml/eng/registration.wsd)
- **Diagram (SVG preview)**:

  ![Registration & Email Verification](docs/uml/eng/export/RegistrationFlow.svg)
- **Purpose**: Describes the end‑to‑end registration flow for a new student.
- **Key steps**:
  - Client-side validation of the registration form (name, institutional email, password & confirmation).
  - Backend checks that the email domain is whitelisted (e.g. `@unibo.it`, `@studio.unibo.it`).
  - Password strength validation (minimum length and character requirements).
  - Detection of already registered emails.
  - OTP (one-time password) generation, hashing, storage in `otp_verifications`, and email delivery.
  - OTP verification with expiry handling, retry on wrong code, and final user creation with initial CFU bonus.

## Meme Lifecycle (Proposal & IPO)
- **Source**: [docs/uml/ita/memesLifecycle.wsd](docs/uml/ita/memesLifecycle.wsd)
- **Diagram (SVG preview)**:

  ![Meme Lifecycle (Proposal & IPO)](docs/uml/eng/export/MemeLifecycle.svg)
- **Purpose**: Models the lifecycle of a meme from user proposal to admin approval or rejection.
- **Key steps**:
  - A student uploads image, title, description, and categories, and confirms the listing.
  - Backend verifies that the user has enough CFU to pay the listing fee.
  - AI-based content analysis to detect inappropriate content.
  - Atomic transaction that charges the fee, creates the meme with status `UPLOADED`, stores initial price in `price_histories`, and exposes the meme to the marketplace.
  - Notification to the Rectorate (admin panel) about the new meme.
  - Admin manual review for policy compliance.
  - On rejection: status updated to `REJECTED`, soft delete, CFU refund, and notification to the student.
  - On approval: admin can tune financial parameters (base price, slope/risk); backend updates status to `APPROVED` and persists the new price.

## Trading Flow (Buy with Slippage Control)
- **Source**: [docs/uml/ita/tradingCore.wsd](docs/uml/ita/tradingCore.wsd)
- **Diagram (SVG preview)**:

  ![Trading Flow (Buy with Slippage Control)](docs/uml/eng/export/TradingFlow.svg)
- **Purpose**: Captures the user experience and backend logic when buying meme shares through the AMM bonding curve with slippage protection.
- **Key steps**:
  - User selects a meme, enters the quantity, and requests an order preview.
  - Frontend calls the backend for a quote; the backend reads the current supply, computes the integral price along the bonding curve, adds fees, and returns an estimated total.
  - The frontend shows a confirmation modal with the order summary.
  - On confirmation, the backend re-reads the up‑to‑date supply and recalculates the real cost.
  - If the price moved beyond the tolerated slippage, the frontend shows an updated‑price alert and the user can re‑preview.
  - If the user has insufficient CFU, an error is returned.
  - Otherwise, a non‑concurrent transaction debits the user balance, updates meme supply, records a historical transaction, and confirms the purchase to the UI.

## Dividends Distribution (Holding Incentive)
- **Source**: [docs/uml/ita/dividendsDistribution.wsd](docs/uml/ita/dividendsDistribution.wsd)
- **Diagram (SVG preview)**:

  ![Dividends Distribution (Holding Incentive)](docs/uml/eng/export/DividendsDistribution.svg)
- **Purpose**: Describes the scheduled job that periodically distributes dividends to long‑term holders.
- **Key steps**:
  - A scheduler triggers the job (e.g. nightly at 02:00).
  - The backend fetches all active memes and iterates over them.
  - For each meme, it evaluates the price trend over the last 24 hours.
  - If the trend is positive:
    - Computes total market value and a global dividend amount (e.g. 1% of market value).
    - Derives a per‑share dividend by dividing by current supply.
    - Takes a snapshot of the holder distribution, then within a transaction:
      - Iterates over each shareholder.
      - Credits the personal dividend in CFU based on quantity held.
      - Persists a transaction record.
    - Commits the transaction.
  - If the trend is not positive, the meme is skipped for that run.
  - At the end, the notifications subsystem sends a global summary to holders, logs completion, and updates the last‑distribution timestamp.
