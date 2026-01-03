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

---

## Team Division Strategy (2 Full-Stack Developers)

The work should be divided based on **domain ownership** and **parallelizable workflows** to maximize velocity and minimize dependencies.

### **Developer A: "Trading & Auth Domain"**

**Phase 1 (Days 1-3):**
- **Backend:** Bootstrap Laravel project, all 15 migrations, Eloquent models with relationships
- **Backend:** Authentication system (registration, OTP, login, 100 CFU bonus)
- **Frontend:** Auth flow UI (Landing Page, Register, Login, OTP verification, Onboarding bonus modal)
- **Backend:** Meme model, image handling, basic CRUD operations

**Phase 2 (Days 4-6):**
- **Backend:** Trading Core implementation - the bonding curve AMM logic, `TradingService`, atomic transactions
- **Frontend:** Trade Station UI (price display, chart integration, buy/sell modals, slippage protection)
- **Backend:** Order execution endpoints and validation

**Phase 3 (Days 7-8):**
- **Backend:** Portfolio calculations (Net Worth, PNL, holdings aggregation)
- **Frontend:** Portfolio dashboard and transaction history
- **Polish:** Toast notifications for trading actions

### **Developer B: "Content & Admin Domain"**

**Phase 1 (Days 1-3):**
- **Frontend:** Design system setup (Tailwind configuration, component library)
- **Frontend:** Meme card components and marketplace feed UI
- **Frontend:** App Shell layout (top bar, bottom navigation, notification panel structure)
- **Frontend:** Marketplace filtering and infinite scroll

**Phase 2 (Days 4-6):**
- **Backend:** Admin panel functionality (meme approval workflow, IPO maker)
- **Frontend:** Admin interface (approval queue, surveillance dashboard, communications ticker)
- **Backend:** File upload handling and validation for meme images
- **Frontend:** Create Meme form with image upload

**Phase 3 (Days 7-8):**
- **Backend:** Leaderboard calculations and ranking logic
- **Frontend:** "Dean's List" leaderboard with podium design
- **Backend:** Scheduled jobs setup (dividends distribution, price updates)
- **Polish:** Skeleton loading states, empty states, pull-to-refresh

### **Parallel Work Opportunities**

- **Days 1-2:** While Dev A sets up the Laravel foundation, Dev B can work on static UI components and design system in a separate branch.
- **Days 3-4:** Dev A focuses on complex trading logic while Dev B builds the content management and admin features.
- **Days 5-6:** Both can work on their respective frontend implementations using the APIs they've built.

### **Integration Points**

- **Day 3:** Merge branches and establish API contracts
- **Day 5:** Integration testing of trading flow end-to-end  
- **Day 7:** Final integration and cross-testing
