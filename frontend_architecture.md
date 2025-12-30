# Architettura Frontend - AlmaStreet

## Panoramica Generale

L'architettura frontend adotta un approccio **Blade Components + JavaScript Modulare** che massimizza il riutilizzo del codice senza ricorrere a framework JavaScript complessi. La struttura si basa su:

- **Server-Side Rendering** tramite Laravel Blade per SEO e performance iniziali
- **Progressive Enhancement** con JavaScript Vanilla per interattività
- **Component-Based Architecture** con Blade Components per UI riutilizzabili
- **Mobile First Design** con layout adattivo desktop/tablet

---

## Principi Architetturali

### 1. Separazione Layout per Contesto Utente

Il sistema prevede **layout multipli** in base al contesto di autenticazione e funzionalità:

```
layouts/
├── app.blade.php          → Utenti autenticati (con App Shell completa)
├── guest.blade.php        → Utenti non autenticati (Landing, Login, Register)
├── admin.blade.php        → Dashboard amministrativa (diversa UX)
└── minimal.blade.php      → Pagine speciali (Trade Station, Onboarding flow)
```

**Criterio di scelta:**
- **app.blade.php**: Pagine che necessitano di Bottom Bar + Top Bar (Market, Classifica, Portafoglio, Profilo)
- **guest.blade.php**: Pagine pubbliche senza navigazione persistente
- **admin.blade.php**: Area amministrativa con sidebar laterale e menu specifici
- **minimal.blade.php**: Pagine full-immersive che richiedono focus totale (es. Trade Station durante operazione)

---

## Struttura Directory Completa

```
resources/
│
├── views/
│   │
│   ├── layouts/                    # Layout base
│   │   ├── app.blade.php           # Shell autenticato (Bottom Bar + Top Bar)
│   │   ├── guest.blade.php         # Shell pubblico (header semplice + footer)
│   │   ├── admin.blade.php         # Shell admin (sidebar + top navbar)
│   │   └── minimal.blade.php       # Shell minimale (solo top bar minimale)
│   │
│   ├── components/                 # Componenti riutilizzabili
│   │   │
│   │   ├── navigation/             # Elementi di navigazione
│   │   │   ├── bottom-bar.blade.php       # Bottom navigation mobile (5 items)
│   │   │   ├── top-bar.blade.php          # Top bar contestuale
│   │   │   ├── top-navbar.blade.php       # Top navbar desktop
│   │   │   ├── ticker.blade.php           # Marquee ticker (solo marketplace)
│   │   │   └── notifications-panel.blade.php  # Aside notifiche
│   │   │
│   │   ├── meme/                   # Componenti relativi ai meme
│   │   │   ├── card.blade.php             # Card meme standard (feed)
│   │   │   ├── card-compact.blade.php     # Card compatta (lista)
│   │   │   ├── card-skeleton.blade.php    # Skeleton loading per card
│   │   │   ├── mini-card.blade.php        # Mini card (portafoglio)
│   │   │   ├── detail-header.blade.php    # Header pagina dettaglio
│   │   │   └── preview.blade.php          # Anteprima meme (paywall landing)
│   │   │
│   │   ├── trading/                # Componenti trading
│   │   │   ├── order-modal.blade.php      # Modal bottom sheet buy/sell
│   │   │   ├── price-header.blade.php     # Header finanziario (prezzo + variazione)
│   │   │   ├── quantity-input.blade.php   # Input quantità con slider
│   │   │   ├── order-summary.blade.php    # Riepilogo ordine (prezzo, fee, totale)
│   │   │   └── action-bar.blade.php       # Barra azioni sticky (Compra/Vendi)
│   │   │
│   │   ├── portfolio/              # Componenti portafoglio
│   │   │   ├── net-worth-hero.blade.php   # Hero section Net Worth
│   │   │   ├── allocation-chart.blade.php # Grafico asset allocation
│   │   │   ├── position-row.blade.php     # Riga posizione asset
│   │   │   └── pnl-badge.blade.php        # Badge PNL giornaliero
│   │   │
│   │   ├── leaderboard/            # Componenti classifica
│   │   │   ├── user-rank-row.blade.php    # Riga utente in classifica
│   │   │   ├── podium.blade.php           # Podio top 3
│   │   │   └── badge-showcase.blade.php   # Showcase badge utente
│   │   │
│   │   ├── admin/                  # Componenti admin
│   │   │   ├── ipo-form.blade.php         # Form IPO Maker
│   │   │   ├── metric-card.blade.php      # Card metrica dashboard
│   │   │   ├── whale-alert.blade.php      # Alert whale
│   │   │   └── suspension-modal.blade.php # Modal sospensione titolo
│   │   │
│   │   ├── ui/                     # Componenti UI generici
│   │   │   ├── badge-change.blade.php     # Badge variazione % (rosso/verde)
│   │   │   ├── toast.blade.php            # Toast notification
│   │   │   ├── chip.blade.php             # Filtro chip
│   │   │   ├── modal.blade.php            # Modal generico
│   │   │   ├── skeleton.blade.php         # Skeleton generico
│   │   │   ├── chart-container.blade.php  # Wrapper grafico
│   │   │   ├── empty-state.blade.php      # Stato vuoto
│   │   │   └── loading-spinner.blade.php  # Spinner caricamento
│   │   │
│   │   └── forms/                  # Componenti form
│   │       ├── input-field.blade.php      # Input text/email/password
│   │       ├── textarea-field.blade.php   # Textarea
│   │       ├── select-field.blade.php     # Select dropdown
│   │       ├── file-upload.blade.php      # Upload file (per meme)
│   │       ├── button.blade.php           # Button primario/secondario
│   │       └── validation-error.blade.php # Messaggio errore validazione
│   │
│   └── pages/                      # View pagine complete
│       │
│       ├── landing.blade.php       # Landing page (guest)
│       │
│       ├── auth/                   # Autenticazione
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── verify-otp.blade.php
│       │   └── forgot-password.blade.php
│       │
│       ├── marketplace/            # Marketplace
│       │   └── index.blade.php     # Homepage marketplace (feed meme)
│       │
│       ├── meme/                   # Dettaglio meme e trading
│       │   ├── show.blade.php      # Dettaglio meme + Trade Station
│       │   └── create.blade.php    # Form creazione meme (listing)
│       │
│       ├── portfolio/              # Portafoglio
│       │   ├── index.blade.php     # Dashboard portafoglio
│       │   └── history.blade.php   # Storico transazioni
│       │
│       ├── leaderboard/            # Classifica
│       │   └── index.blade.php     # Leaderboard globale
│       │
│       ├── profile/                # Profilo utente
│       │   ├── show.blade.php      # Visualizza profilo
│       │   ├── edit.blade.php      # Modifica profilo
│       │   └── settings.blade.php  # Impostazioni account
│       │
│       └── admin/                  # Area admin
│           ├── dashboard.blade.php
│           ├── ipo-maker.blade.php
│           ├── surveillance.blade.php
│           ├── communications.blade.php
│           └── users.blade.php
│
└── js/                             # JavaScript modulare
    │
    ├── app.js                      # Entry point principale
    │
    ├── core/                       # Core utilities
    │   ├── api.js                  # Wrapper fetch API con error handling
    │   ├── events.js               # Event bus centralizzato
    │   ├── state.js                # State management semplice (Observer pattern)
    │   └── storage.js              # LocalStorage wrapper
    │
    ├── services/                   # Business logic services
    │   ├── TradingService.js       # Logica trading (preview ordini, calcolo slippage)
    │   ├── PriceUpdateService.js   # Polling prezzi (long polling/SSE)
    │   ├── NotificationService.js  # Gestione toast e notifiche
    │   ├── PortfolioService.js     # Calcolo Net Worth, PNL
    │   └── AuthService.js          # Gestione sessione, logout
    │
    ├── components/                 # Componenti JavaScript
    │   ├── Chart.js                # Wrapper Chart.js/TradingView
    │   ├── OrderModal.js           # Logica modal ordini con validazione
    │   ├── InfiniteScroll.js       # Scroll infinito marketplace
    │   ├── Ticker.js               # Ticker animato (marquee)
    │   ├── NotificationPanel.js    # Aside notifiche con slide animation
    │   └── ImageUploader.js        # Upload immagine meme con preview
    │
    ├── pages/                      # Script specifici per pagina
    │   ├── marketplace.js          # Logica marketplace (filtri, feed)
    │   ├── trading.js              # Logica Trade Station (grafici, ordini)
    │   ├── portfolio.js            # Logica portafoglio (charts, aggiornamenti)
    │   ├── leaderboard.js          # Logica classifica (animazioni rank)
    │   └── admin-dashboard.js      # Logica dashboard admin
    │
    └── utils/                      # Utility functions
        ├── format.js               # Formattazione numeri, date, CFU
        ├── validation.js           # Validazione form client-side
        ├── dom.js                  # Helper DOM manipulation
        ├── animation.js            # Helper animazioni (fade, slide)
        └── debounce.js             # Debounce e throttle
```

## Pattern di Riutilizzo Codice

### 1. Blade Component Composition

**Principio:** Componenti grandi composti da componenti piccoli

**Esempio concettuale:**
```
<x-meme.card> (componente complesso)
    ├── <x-ui.badge-change />  (componente atomico riutilizzabile)
    ├── <img> (standard HTML)
    └── <x-forms.button />  (componente atomico riutilizzabile)
```

Questo permette di:
- Cambiare stile badge in un posto solo
- Riusare badge anche in altri context (portfolio, leaderboard)
- Testare componenti atomici indipendentemente

---

### 2. JavaScript Service Injection

**Principio:** Component JavaScript importano services invece di duplicare logica

**Esempio flusso:**
```
OrderModal.js
    ├── import TradingService (per preview/execute)
    ├── import NotificationService (per feedback)
    └── import ValidationService (per controlli)

PortfolioPage.js
    ├── import TradingService (riuso stesso service)
    └── import PortfolioService (logica specifica)
```

**Vantaggio:**
- Un bug fix in TradingService si propaga ovunque automaticamente
- No codice duplicato per calcoli/validazioni
- Facile mock nei test

---

### 3. CSS Utility Classes (Tailwind/Flowbite)

**Principio:** Composizione classi invece di CSS custom

**Pattern:**
```
<!-- Riuso tramite classi semantiche -->
<div class="price-positive">+12.5%</div>  <!-- bg-green-100 text-green-800 -->
<div class="price-negative">-3.2%</div>   <!-- bg-red-100 text-red-800 -->
```

Definire utility classes custom in `app.css`:
```
@layer components {
    .price-positive { @apply bg-green-100 text-green-800 font-bold; }
    .price-negative { @apply bg-red-100 text-red-800 font-bold; }
    .card-base { @apply bg-gray-800 rounded-lg p-4 shadow-lg; }
}
```

---

### 4. Blade Include per Partial Ripetitive

**Pattern:** Per snippet HTML non parametrizzabili

**Esempio:**
```
<!-- resources/views/partials/loading-spinner.blade.php -->
<div class="flex justify-center items-center p-8">
    <svg class="animate-spin h-8 w-8 text-blue-500">...</svg>
</div>

<!-- Uso -->
@include('partials.loading-spinner')
```

**Differenza con Component:**
- Include: No props, semplice stampa HTML
- Component: Props, logica, riutilizzo semantico

---

### 5. Event-Driven Architecture per Decoupling

**Pattern:** Componenti comunicano via eventi invece di dipendenze dirette

**Esempio scenario:**
```
1. User esegue trade in OrderModal
2. OrderModal emette evento "trade:executed"
3. PortfolioWidget ascolta evento e aggiorna Net Worth
4. NotificationService ascolta evento e mostra toast
5. PriceUpdateService ascolta evento e refresha prezzi
```

**Vantaggio:**
- OrderModal non sa chi/quanti ascoltano
- Aggiungere nuovo listener non richiede modifiche a OrderModal
- Facile disabilitare feature senza rompere codice

---

## Flow di Sviluppo Consigliato

### Fase 1: Setup Struttura Base
1. Creare struttura directory completa
2. Implementare layout base (app, guest, admin, minimal)
3. Creare componenti navigation (top bar, bottom bar)
4. Setup Vite/Laravel Mix per JavaScript modules
5. Implementare core utilities (api, events, state)

### Fase 2: Componenti UI Atomici
1. Badge, button, input, toast (componenti più piccoli)
2. Testare componenti in isolation (Storybook opzionale)
3. Documentare props e usage in README

### Fase 3: Componenti Complessi
1. Meme card (compone badge + button)
2. Order modal (compone input + button + recap)
3. Price header (compone badge + icon)

### Fase 4: Pagine Complete
1. Marketplace (usa infinite scroll + meme cards)
2. Trading Station (usa price header + chart + order modal)
3. Portfolio (usa net worth hero + mini cards + chart)
4. Leaderboard (usa rank rows + podium)

### Fase 5: JavaScript Interactivity
1. Services (trading, price update, notifications)
2. Components (chart, modal logic, infinite scroll)
3. Page-specific scripts (marketplace.js, trading.js, etc.)

### Fase 6: Polish & Optimization
1. Skeleton loading states
2. Error boundaries
3. Responsive refinement
4. Performance optimization (lazy load, debounce)
5. Accessibility (aria labels, keyboard navigation)

---

## Checklist Riutilizzo Codice

Prima di scrivere nuovo codice, verificare:

- [ ] Esiste già un component Blade che fa questa cosa?
- [ ] Posso comporre component esistenti invece di crearne uno nuovo?
- [ ] Questa logica JavaScript è specifica o può essere un service?
- [ ] Questo style è ripetuto? Posso creare una utility class?
- [ ] Questo snippet HTML è duplicato? Posso usare @include o component?
- [ ] Questa validazione è già implementata in validation.js?
- [ ] Questo formato è già implementato in format.js?
- [ ] Posso usare eventi invece di dipendenze dirette?

**Golden Rule:** Se copi-incolli codice due volte, alla terza volta refactorizza in component/service/utility.

---

## Note Finali

Questa architettura bilancia:
- ✅ **Riutilizzo**: Component system + JavaScript modules
- ✅ **Semplicità**: No framework complessi, solo Blade + Vanilla JS
- ✅ **Scalabilità**: Facile aggiungere nuove pagine/feature
- ✅ **Manutenibilità**: Separazione responsabilità, single source of truth
- ✅ **Performance**: Server-side rendering + progressive enhancement
- ✅ **Developer Experience**: Struttura chiara, convenzioni consistenti

La chiave è **non forzare il riutilizzo**: se un component diventa troppo complicato per gestire 10 casi diversi, meglio creare 2 component semplici.

**Pragmatismo > Purezza architetturale**
