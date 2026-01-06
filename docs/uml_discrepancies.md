# UML Implementation Analysis Report

## 1. Meme Lifecycle (`memesLifecycle.wsd`)
Analysis of alignment with `project.md`:

*   **CRITICAL CONFLICT (Refund Policy):**
    *   **Project Spec:** Explicitly states the Listing Fee is **non-refundable** ("non rimborsabile") to discourage spam.
    *   **UML:** Depicts a **Refund** step ("Refund CFU allo Studente") if the Admin rejects the meme.
*   **Feature Creep (AI Analysis):**
    *   **UML:** Includes an automated "Analisi immagine con IA" step.
    *   **Project Spec:** No mention of AI integration for image content analysis.
*   **Terminology:**
    *   UML uses status `UPLOADED`/`REJECTED` vs Spec `pending`/`suspended`.

## 2. Trading Core (`tradingCore.wsd`)
Analysis of alignment with `project.md`:

*   **Slippage Logic (Minor):**
    *   **Project Spec:** Implies a strict check ("If the price has changed...").
    *   **UML:** Introduces a **Tolerance** threshold ("Variazione Prezzo > Tolleranza?").
*   **Portfolio Logic (Omission):**
    *   **Project Spec:** Explicitly mandates calculating and updating the `avg_buy_price` in the Portfolio using a specific weighted average formula.
    *   **UML:** Shows updating User Balance and Meme Supply, but omits the explicit step of updating the **User Portfolio/Average Buy Price** record.

## 3. Dividends Distribution (`dividendsDistribution.wsd`)
Analysis of alignment with `project.md`:

*   **Missing Data Record:**
    *   **Project Spec:** Defines a `dividend_histories` table to track the global dividend event (amount per share, total distributed).
    *   **UML:** Shows recording individual user transactions, but **omits** the step to create the global `DividendHistory` record for the meme.
*   **Feature Creep (Notification):**
    *   **UML:** Adds a "Send Summary Notification" step.
    *   **Project Spec:** Does not explicitly require a notification for dividends, only the balance credit and transaction record.