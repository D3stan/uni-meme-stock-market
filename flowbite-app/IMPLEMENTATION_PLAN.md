# AlmaStreet - Implementation Plan
## Fast-Track Incremental Development Strategy

> **Philosophy**: Build the core trading loop first, then add layers. Focus on "working demo" over "production polish".

---

## **PHASE 1: Foundation & Core User Flow**
*Goal: Users can register, login, and see their dashboard*

### Backend Tasks
- [ ] **Database Setup**
  - Create migration for `users` table (extend default with `cfu_balance`, `role`, `is_suspended`)
  - Create migration for `global_settings` table
  - Seed admin user and basic settings (listing_fee: 20, tax_rate: 0.02)
  
- [ ] **Authentication System**
  - Implement registration with email validation (simple token-based, not OTP yet)
  - Basic login/logout functionality
  - Middleware for role checking (admin vs trader)
  - Auto-assign 100 CFU bonus on registration

- [ ] **User Profile API**
  - GET `/api/user/profile` - Return user data + CFU balance
  - PATCH `/api/user/profile` - Update nickname, profile picture

### Frontend Tasks
- [ ] **Layout Structure**
  - Create responsive navbar component (sticky top/bottom based on breakpoint)
  - Create main layout wrapper with content area
  - Add basic routing structure (home, profile, login, register)

- [ ] **Auth Pages**
  - Registration form with validation feedback
  - Login form
  - Simple welcome page

- [ ] **Dashboard Skeleton**
  - Empty state for "My Portfolio" page
  - Display current CFU balance
  - Placeholder for asset list

---

## **PHASE 2: Market Core - Meme CRUD & Display**
*Goal: Admin can add memes, users can browse them*

### Backend Tasks
- [ ] **Meme System - Database**
  - Create `categories` table migration
  - Create `memes` table migration (all fields from schema)
  - Create `price_histories` table migration
  - Seed 3-5 sample categories
  - Seed 10-15 sample memes with realistic base_price/slope

- [ ] **Meme CRUD Endpoints**
  - POST `/api/admin/memes` - Create meme (admin only, auto-approve for demo)
  - GET `/api/memes` - List all approved memes (with pagination)
  - GET `/api/memes/{id}` - Single meme details + current price
  - PATCH `/api/admin/memes/{id}` - Update meme (suspend/activate)
  - DELETE `/api/admin/memes/{id}` - Soft delete

- [ ] **Price Calculation Logic**
  - Create `MemeService::calculateCurrentPrice($meme)` helper
  - Formula: `base_price + (slope * circulating_supply)`
  - Update `current_price` field on every transaction

### Frontend Tasks
- [ ] **Marketplace (Homepage)**
  - Grid layout (2 columns on mobile, 3+ on desktop)
  - Meme card component (image, title, price, 24h change badge)
  - Category filter dropdown
  - Click card → navigate to Trade Station

- [ ] **Admin Panel - Meme Management**
  - Simple table view of all memes
  - "Approve/Suspend" toggle buttons
  - Form to create new meme (upload image, set base_price, slope, category)

---

## **PHASE 3: Trading Engine - Buy/Sell Mechanism**
*Goal: Users can buy and sell memes, prices update dynamically*

### Backend Tasks
- [ ] **Portfolio System**
  - Create `portfolios` table migration
  - Create `transactions` table migration
  - Constraint: UNIQUE(user_id, meme_id) on portfolios

- [ ] **Trading Service**
  - `TradeService::executeBuy($user, $meme, $quantity)`
    - Check CFU balance (cost = price * quantity + fee)
    - Atomic transaction: deduct CFU, mint shares, create portfolio entry
    - Update `circulating_supply` (+quantity)
    - Recalculate `current_price`
    - Log in `transactions` table
    - Create `price_histories` record
  
  - `TradeService::executeSell($user, $meme, $quantity)`
    - Check portfolio ownership
    - Atomic transaction: add CFU, burn shares, update portfolio
    - Update `circulating_supply` (-quantity)
    - Recalculate `current_price`
    - Log in `transactions` table
    - Create `price_histories` record

- [ ] **Trading Endpoints**
  - POST `/api/trade/buy` - Execute purchase
  - POST `/api/trade/sell` - Execute sale
  - GET `/api/portfolio` - User's current holdings
  - GET `/api/transactions` - User's transaction history

### Frontend Tasks
- [ ] **Trade Station Page**
  - Header block: Meme name, gigantic current price, 24h % change
  - Image display section
  - Tab switcher: BUY / SELL
  - Quantity input with +/- buttons
  - Live calculation display (JS):
    - `Price per share * Quantity + Fee (2%) = Total`
  - "CONFIRM ORDER" button (green for buy, red for sell)
  - Handle API call and show success/error toast

- [ ] **Portfolio Page - Asset List**
  - Fetch `/api/portfolio`
  - Display as cards/list: thumbnail, name, quantity, current value, P&L badge
  - Click asset → redirect to Trade Station

---

## **PHASE 4: Real-Time Data & Charts**
*Goal: Add visual feedback and live price updates*

### Backend Tasks
- [ ] **Price History API**
  - GET `/api/memes/{id}/price-history?range=24h|7d|30d`
  - Return array of `{timestamp, price}` for charting

- [ ] **Market Stats API**
  - GET `/api/market/trending` - Top 5 volatile memes (for ticker)
  - GET `/api/market/stats` - Global stats (total volume, users, etc.)

### Frontend Tasks
- [ ] **Chart Integration**
  - Install TradingView Lightweight Charts library
  - Add price history chart to Trade Station page
  - Touch-optimized, fixed height

- [ ] **Ticker Component**
  - Horizontal scrolling strip below header
  - Auto-fetch `/api/market/trending` every 10s
  - Display: Meme name, price, % change (color coded)

- [ ] **Live Updates (Polling)**
  - On Trade Station page: poll `/api/memes/{id}` every 5s
  - Update price display without full page reload
  - On Portfolio page: poll `/api/portfolio` every 10s to refresh P&L

- [ ] **Net Worth Calculator**
  - Add to Portfolio page header
  - Formula: `CFU Balance + Σ(holdings * current_price)`
  - Display in large font with Chart.js donut chart (Liquid vs Invested)

---

## **PHASE 5: User Proposal Flow**
*Goal: Users can submit memes, pay listing fee*

### Backend Tasks
- [ ] **Meme Proposal Endpoint**
  - POST `/api/memes/propose` - Upload image, title, category
  - Charge listing fee (deduct from CFU balance)
  - Create meme with status: `pending`
  - Create notification for admins

- [ ] **Notifications System**
  - Create `notifications` table migration
  - Helper: `NotificationService::create($user_id, $title, $message)`
  - GET `/api/notifications` - Unread count + list

### Frontend Tasks
- [ ] **FAB Button (Floating Action Button)**
  - Position: fixed bottom-right
  - Icon: "+" or upload icon
  - Click → open modal/page with proposal form

- [ ] **Meme Proposal Form**
  - Image upload (with preview)
  - Title input
  - Category dropdown
  - Show listing fee amount
  - Submit → show success message and remaining balance

- [ ] **Admin Notification Panel**
  - Badge on admin navbar showing unread count
  - List of pending meme approvals
  - Click → quick approve/reject action

---

## **PHASE 6: Admin Dashboard & Monitoring**
*Goal: Admin has full control panel and market surveillance*

### Backend Tasks
- [ ] **Admin Analytics Endpoints**
  - GET `/api/admin/dashboard` - Key metrics:
    - Total users, total memes, total CFU in circulation
    - Total fees collected (sum from transactions)
    - Top 10 users by net worth (leaderboard data)
  
  - GET `/api/admin/surveillance` - Anti-fraud data:
    - Top gainers/losers (last 24h)
    - Whale alert (users with >10% supply of any meme)
    - Volume anomalies

- [ ] **Market Communications**
  - Create `market_communications` table migration
  - POST `/api/admin/communications` - Create banner message
  - GET `/api/communications/active` - Get current active message

### Frontend Tasks
- [ ] **Admin Dashboard Page**
  - Card grid with key metrics (users, memes, volume, fees)
  - Quick action buttons (suspend trading, send message)

- [ ] **Market Surveillance Panel**
  - Table: Top gainers/losers with extreme % changes
  - Table: Whale alerts (flaggable accounts)
  - Chart: Volume over time

- [ ] **Global Message Banner**
  - Fetch `/api/communications/active` on app load
  - Display as dismissible ticker at top (admin message)

---

## **PHASE 7: Gamification & Engagement**
*Goal: Add leaderboard, badges, dividends*

### Backend Tasks
- [ ] **Leaderboard System**
  - GET `/api/leaderboard` - Top 50 users by net worth
  - Cache for 5 minutes to reduce load

- [ ] **Badges System**
  - Create `badges` table migration
  - Create `user_badges` table migration
  - Seed initial badges (Diamond Hands, IPO Hunter, Liquidator)
  - Scheduled job or event listener to award badges:
    - Check holdings age for Diamond Hands
    - Count IPO participations for IPO Hunter
    - Check balance for Liquidator

- [ ] **Dividends Mechanism**
  - Create `dividend_histories` table migration
  - Scheduled job (daily): `php artisan dividends:distribute`
    - For each meme with positive 24h trend:
      - Calculate 1% of total market cap
      - Snapshot current holders from `portfolios`
      - Distribute proportionally
      - Log in `transactions` as type: `dividend`

### Frontend Tasks
- [ ] **Leaderboard Page**
  - "Dean's List" title
  - Ranked list with rank number, username, net worth
  - Highlight current user

- [ ] **Badge Display**
  - Add badges section to user profile page
  - Show earned badges with icons and descriptions
  - Gray out unearned badges

- [ ] **Dividend Notifications**
  - Toast/banner when user receives dividend
  - Transaction history shows dividend entries clearly

---

## **PHASE 8: Polish & UX Enhancements**
*Goal: Make it feel complete and smooth*

### Backend Tasks
- [ ] **Watchlist Feature**
  - Create `watchlists` table migration
  - POST `/api/watchlist/{memeId}` - Add to watchlist
  - DELETE `/api/watchlist/{memeId}` - Remove
  - GET `/api/watchlist` - User's watchlist

- [ ] **Transaction History Filters**
  - Enhance GET `/api/transactions` with query params:
    - `?type=buy|sell|dividend|listing_fee`
    - `?meme_id=X`
    - Pagination

- [ ] **Email Verification with OTP**
  - Replace token-based with OTP system
  - Create `otps` table (separate utility table as per notes)
  - Send 6-digit code via email
  - Verify on separate page

### Frontend Tasks
- [ ] **Search & Filters**
  - Add search bar to Marketplace (filter by meme name)
  - Sort options: Price (high/low), Most volatile, Newest

- [ ] **Watchlist UI**
  - Heart/star icon on meme cards to add to watchlist
  - Dedicated "My Watchlist" page

- [ ] **Transaction History Table**
  - Paginated table with filters
  - Export to CSV button (bonus)

- [ ] **Loading States & Error Handling**
  - Skeleton loaders for all data fetching
  - Proper error messages (insufficient funds, network errors)
  - Toast notifications for all actions

- [ ] **Responsive Design Audit**
  - Test all pages on mobile (priority)
  - Ensure navbar switches correctly (top/bottom)
  - Touch-friendly buttons (min 44px tap target)

---

## **PHASE 9: Final Testing & Seeding**
*Goal: Demo-ready application with realistic data*

### Backend Tasks
- [ ] **Comprehensive Database Seeding**
  - Seed 50 users (mix of active traders and dormant accounts)
  - Seed 30+ memes across all categories (varied slopes)
  - Seed realistic transaction history (last 7 days)
  - Seed price histories to make charts interesting
  - Seed some portfolios with holdings
  - Award badges to random users

- [ ] **API Rate Limiting**
  - Add throttle middleware to prevent abuse (optional but quick)

- [ ] **Validation Refinement**
  - Ensure all endpoints have proper FormRequest validation
  - Return meaningful error messages

### Frontend Tasks
- [ ] **Demo Walkthrough Flow**
  - Create tutorial/onboarding modal for first-time users
  - Highlight key features (FAB, Trade Station, Leaderboard)

- [ ] **Visual Polish**
  - Add meme-themed animations (stonks arrow up, Doge coin spin)
  - Fun 404 page (meme-themed)
  - Loading spinner with meme icon

- [ ] **Cross-Browser Testing**
  - Test on Chrome, Firefox, Safari (mobile)
  - Fix any layout issues

- [ ] **Performance Check**
  - Optimize images (compress uploads)
  - Lazy load meme images on Marketplace
  - Check API response times

---

## **PHASE 10: Optional Enhancements (Time Permitting)**
*These can be skipped if time is tight*

### Backend
- [ ] Account deactivation/deletion endpoint
- [ ] Admin action logging table (`admin_actions`)
- [ ] Advanced surveillance (pump-and-dump detection algorithm)

### Frontend
- [ ] Dark mode toggle
- [ ] Accessibility audit (ARIA labels, keyboard navigation)
- [ ] PWA features (installable, offline mode)
- [ ] Advanced charts (TradingView candlesticks, volume bars)

---

## **Success Criteria for Demo**
- [ ] User can register and receive 100 CFU
- [ ] User can browse 30+ memes with real-time prices
- [ ] User can buy meme shares (price increases)
- [ ] User can sell meme shares (price decreases)
- [ ] Admin can approve/suspend memes
- [ ] Leaderboard shows top traders
- [ ] Charts display price history
- [ ] Mobile-responsive (one-handed use)
- [ ] No critical bugs during 15-minute demo

---

## **Technology Stack Decisions**

### Backend (Laravel 12)
- **ORM**: Eloquent with relationships
- **Validation**: FormRequest classes
- **Jobs**: Queue for badge awarding, dividends (can use `sync` driver for demo)
- **Storage**: Local disk for meme images
- **Database**: MySQL (already configured)

### Frontend (Vanilla JS + Flowbite)
- **CSS Framework**: Tailwind CSS (already installed)
- **Components**: Flowbite (pre-built Tailwind components)
- **Charts**: Chart.js for donut chart, TradingView Lightweight Charts for price history
- **HTTP Client**: Fetch API
- **State Management**: None needed (vanilla JS with DOM manipulation)
- **Routing**: Simple SPA with History API or multi-page app

### Deployment (Not Critical for Demo)
- Local development with `php artisan serve` and `npm run dev`
- Optional: Docker with Laravel Sail (already present)

---

## **Risk Mitigation Notes**

1. **Atomic Transactions Critical**: Use Laravel DB transactions for ALL buy/sell operations. Test rollback scenarios.

2. **Race Conditions**: For demo, single-threaded PHP is fine. For production, would need row-level locking.

3. **Decimal Precision**: Use `DECIMAL(15,4)` everywhere. Never use FLOAT for money.

4. **Image Upload Security**: Validate MIME types, limit file size (2MB), sanitize filenames.

5. **CFU Balance Integrity**: Every transaction MUST log `cfu_balance_after`. Reconciliation check endpoint for debugging.

6. **Price Calculation Consistency**: Always recalculate from formula, never trust cached values as source of truth.

---

## **Development Workflow Tips**

- **Migrations First**: Write all migrations before touching models/controllers
- **Test Routes with Postman/Thunder Client**: Verify API before building frontend
- **Seeder-Driven Development**: Build comprehensive seeders early; reset DB often
- **Component-Based Frontend**: Create reusable JS classes (MemeCard, TradeForm, etc.)
- **Git Branching**: One branch per phase, merge when stable
- **Daily Demo**: End each coding session with a working demo (even if incomplete)

---

**Total Phases**: 10 (7 core, 3 optional)  
**Minimum Viable Demo**: Phases 1-7  
**Recommended**: Phases 1-8  
**Stretch Goals**: Phases 9-10
