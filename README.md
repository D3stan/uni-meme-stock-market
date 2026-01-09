# 📈 AlmaStreet - The Academic Stock Market

> *"Buy the dip, hodl the memes, graduate with gains."*

A university-themed stock market simulator where students trade meme assets using CFU (Credito Finanziario Universitario). Powered by an Automated Market Maker (AMM) bonding curve for guaranteed liquidity and instant trading.

## 🎯 Project Overview

AlmaStreet transforms campus culture into a dynamic trading platform. Students can:
- 📊 **Trade memes** with real-time pricing based on supply and demand
- 💎 **Build portfolios** and compete on the leaderboard
- 🚀 **List new memes** (for a fee) and become the next market maker
- 🏆 **Earn badges** through gamification mechanics
- 💰 **Collect dividends** from performing assets

Unlike traditional order-book markets, AlmaStreet uses a **Linear Bonding Curve** AMM that mints/burns shares on demand, ensuring you can always trade - no waiting for counterparties.

## 🛠 Tech Stack

**Backend**
- Laravel 12 (PHP 8.4)
- Eloquent ORM with atomic transactions
- MySQL database
- Pest 4 for testing

**Frontend**
- Vanilla JavaScript (no framework overhead)
- Flowbite/Bootstrap for UI components
- TradingView Lightweight Charts
- Mobile-first, thumb-driven design

## ⚡ Key Features

### Trading Engine
- **Instant execution** against AMM protocol
- **Dynamic pricing**: `P = P_base + (M × S)` where S = circulating supply
- **Slippage protection** with preview modals
- **Transaction fees** (2%) to prevent scalping

### Gamification
- Real-time leaderboard
- Achievement badges (Diamond Hands, IPO Hunter, etc.)
- Daily dividend distribution for long-term holders

### Admin Panel ("Il Rettorato")
- IPO approval system with pricing control
- Market surveillance dashboard
- Whale alerts and anomaly detection
- Global announcements ticker

## 🚀 Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd uni-meme-stock-market
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup database**
   ```bash
   php artisan migrate --seed
   ```

5. **Start local dev server**
   ```bash
   composer run dev
   ```

6. **Enjoy**  
    * navigate to http://localhost:8000
    * login with:  
        * admin@studio.unibo.it / password
        * mario.rossi@studio.unibo.it / password 
   

## 🎓 Academic Context

This project is built for academic demonstration purposes, showcasing:
- Complex state management with Laravel's Eloquent ORM
- Financial transaction atomicity and concurrency handling
- Real-time data updates without WebSockets
- Mobile-first responsive design patterns
- Gamification mechanics in web applications

## 🔐 Authentication

Registration requires institutional email verification via OTP. New users receive **100 CFU** as starting capital.

## 🤝 Contributing

This is an academic project. If you're a fellow student or want to suggest improvements, feel free to open issues or PRs.

---

*Made with ☕ and a questionable amount of meme research*

**Disclaimer:** No actual CFU were harmed in the making of this application. Please don't try to trade your real university credits.
