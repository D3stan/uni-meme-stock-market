# Plan: AlmaStreet Showcase Project Implementation

This plan prioritizes **speed to MVP** for a showcase project. The strategy is vertical slice development: get one complete feature working end-to-end before moving to the next, allowing early demos while work continues.

---

### Implementation Steps

1.  **Bootstrap Laravel Project and Database Layer**
    * Initialize Laravel and install Tailwind/Flowbite.
    * Create all 15 migrations based on the `project.md` schema.
    * Build Eloquent models with defined relationships.
    * Populate seeders with realistic demo data (10+ memes, 5+ users).

2.  **Implement Auth + Marketplace Vertical Slice**
    * Build registration with OTP (use fake email in dev) and login.
    * Apply 100 CFU signup bonus logic.
    * Develop `guest` and `app` layouts.
    * Create meme cards and marketplace feed with basic filtering.
    * *Goal: Demoable app interface in ~3 days.*

3.  **Build Trading Core with Bonding Curve AMM**
    * Implement `TradingService` using the linear bonding curve formula from `tradingCore.wsd`.
    * Ensure atomic transactions with database locks.
    * Apply 2% fee deduction logic.
    * Develop order modal with slippage preview.
    * *Note: This is the highest complexity module.*

4.  **Add Portfolio and Leaderboard Views**
    * Create portfolio dashboard with current holdings and Net Worth calculation.
    * Implement transaction history view.
    * Build "Dean’s List" leaderboard with podium component.
    * Reuse `meme/mini-card` and `ui/badge-change` components.

5.  **Implement Admin Panel (IPO + Monitoring)**
    * Build `admin` layout with IPO Maker form and meme approval workflow.
    * Create basic surveillance dashboard (whale detection query).
    * Add communications ticker for platform-wide updates.
    * *Note: Skip audit logging for showcase purposes.*

6.  **Polish with Charts and Scheduled Jobs**
    * Integrate TradingView Lightweight Charts for price action.
    * Implement `PriceUpdateService` polling.
    * Create Artisan command for nightly dividends (demo via `php artisan dividends:distribute`).
    * Add skeleton loading states and toast notifications.

---

### Further Considerations

* **Skip Email Integration?** Use a fixed OTP (e.g., `123456`) in development to bypass SMTP setup. Only implement real email if time permits.
* **Database Choice:** Use **SQLite** for maximum portability during demos. Switch to **MySQL** only if showcasing concurrent trading stress tests is a specific requirement.
* **Deprioritize/Stub:** The following features can be omitted or stubbed without impacting the core "Showcase" value:
    * Badge system
    * Watchlists
    * Detailed dividend history
    * AI content moderation