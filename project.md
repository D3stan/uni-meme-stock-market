# Progetto #6: Meme Street (The Academic Stock Market)

## Visione del Progetto
Un'applicazione web gestionale che simula una borsa valori all'interno dell'ateneo. Esistono **Ordini di Acquisto** e **Ordini di Vendita**.
La valuta di scambio sono i **CFU (Credito Finanziario Universitario)**. L'obiettivo degli studenti è massimizzare il valore del proprio portafoglio speculando sulla popolarità dei contenuti (Meme) generati dalla community.

---

## Stack Tecnologico Scelto
* **Backend:** Framework **Laravel** (PHP). Verranno utilizzati:
    * **Eloquent ORM:** Per la gestione del database e delle relazioni.
    * **Migrations & Seeders:** Per la creazione dello schema e il popolamento iniziale di dati finti (utenti, meme, storico transazioni) necessari per la demo.
    * **DB Transactions:** Per garantire l'atomicità delle operazioni finanziarie.
* **Frontend:** HTML5, CSS, **Javascript "Vanilla"** (senza framework come React/Vue/Angular), TradingView Lightweight Charts per i grafici.

---

## Dettagli Funzionali e Implementativi (32 Punti)

### 1. Registrazione e Login (4 punti)
* **Bonus Matricola:** Il sistema prevede un meccanismo di incentivazione all'ingresso. Ogni nuovo utente riceve una dotazione iniziale di **100 CFU**.
* **Verifica Copertura:** Middleware o controlli lato server monitorano la disponibilità economica prima di ogni transazione, impedendo operazioni se il saldo è insufficiente.

### 2. Profilo Utente: "Il Portafoglio" (4 punti)
Una dashboard finanziaria personale, non un profilo social.
* **Asset Allocation:** Grafico a torta (Chart.js) che mostra la composizione del patrimonio (Liquidità vs Investimenti).
* **Valore Netto (Net Worth):** Indicatore calcolato in tempo reale: `Saldo Liquido + (Azioni Possedute * Prezzo Attuale)`.
* **Storico Transazioni:** Tabella paginata con ogni movimento: data, tipo, prezzo unitario e fee pagate.

### 3. Gestione Admin: "Il Rettorato" (8 punti - CRUD)
* **IPO Maker (Create):** L'Admin approva i meme caricati, stabilendo il prezzo di lancio. Questo crea il primo record nello storico dei prezzi.
* **Sospensione Titoli (Update):** L'Admin può bloccare le contrattazioni su un titolo in caso di anomalie di mercato.
* **Monitoraggio (Read):** Dashboard con metriche globali (es. Totale Fee raccolte, Inflazione del server).
* **Delisting (Delete):** Soft delete dei meme "falliti" o obsoleti che non vengono scambiati da tempo.

### 4. Fruizione del Servizio: Trading Core (8 punti)

#### A. Meccanica di Upload (Tassa di Quotazione)
L'utente paga una **Listing Fee** (es. 20 CFU) per proporre un meme. Se il saldo è insufficiente, l'operazione viene bloccata. Questo disincentiva lo spam.

#### B. Algoritmo di Pricing (Curva Logaritmica)
Per garantire una crescita sostenibile del prezzo ed evitare inflazione incontrollata, il valore di un'azione viene calcolato con la seguente formula matematica:

$$P = P_{base} + M \cdot \ln(1 + Q_{tot})$$

Dove:
* **$P$**: Prezzo corrente dell'azione.
* **$P_{base}$**: Prezzo iniziale di IPO (es. 1.00 CFU).
* **$M$**: Moltiplicatore di volatilità (es. 2.0). Un valore più alto rende il prezzo più sensibile agli acquisti.
* **$Q_{tot}$**: Quantità totale di azioni in circolazione per quel meme (investitori totali).
* **$\ln$**: Logaritmo naturale.

**Effetto:** I primi acquisti fanno salire il prezzo velocemente (premiando gli early adopter che hanno scoperto il meme per primi), ma man mano che il meme diventa popolare, serve un volume di acquisti sempre maggiore per far spostare il prezzo in modo significativo.

#### C. Compravendita e Commissioni
* **Fee di Segreteria:** Su ogni transazione viene trattenuta una percentuale (es. 5%). L'utente realizza un profitto solo se `Prezzo Vendita > Prezzo Acquisto + Fee`. Questo meccanismo scoraggia lo "scalping" (compravendita frenetica per micro-guadagni).
* **Atomicità:** Il prelievo dei CFU dal saldo utente, l'accredito delle fee al sistema e l'assegnazione delle azioni avvengono in un unico blocco indivisibile. Se una qualsiasi di queste operazioni fallisce, tutto viene annullato (rollback), garantendo che non si perdano soldi o azioni nel nulla.

---

## Schema Interfacce (UI/UX Mobile First)

L'interfaccia è progettata per l'uso su smartphone a una mano (**Stacked Layout**), evitando colonne affiancate.

### 1. Marketplace (Homepage)
* **Bottom Navbar Sticky:** Logo + Saldo CFU + Icona Menu.
* **Ticker:** Striscia scorrevole sotto l'header con i titoli più volatili.
* **Lista Titoli (Feed Verticale):**
    * Layout a **Card Verticali** (una sotto l'altra).
    * **Contenuto Card:**
        * [Immagine Meme - Larghezza 100%]
        * Riga Info: Titolo (Bold) | Prezzo (Grande) | Badge Variazione 24h (Verde/Rosso).
        * Footer Card: Bottone "Dettagli/Trade" (Full width).

### 2. Trade Station (Pagina Operativa)
Pagina dedicata all'acquisto/vendita, strutturata a blocchi verticali.
* **Blocco 1 (Header):** Nome Meme, Prezzo Attuale gigante, Variazione %.
* **Blocco 2 (Visualizzazione):**
    * Immagine Meme.
    * **Grafico Interattivo (Chart.js):** Altezza fissa, ottimizzato per touch.
* **Blocco 3 (Pannello Comandi - Sticky Bottom):**
    * **Tab Switch:** [ COMPRA ] [ VENDI ].
    * **Input Area:** Campo "Quantità" con pulsanti +/- grandi.
    * **Riepilogo Live (JS):** *Prezzo x Qta + Fee = Totale*.
    * **Bottone Azione:** "CONFERMA ORDINE" (Colore distinto per Buy/Sell).

### 3. Il Mio Portafoglio (Dashboard)
* **Card Riepilogo (Top):** Grafico a Ciambella (Liquidità vs Investito) e Net Worth.
* **Lista Asset (List View):**
    * Righe cliccabili con: Miniatura, Nome, Valore Totale posseduto, P&L (Badge colorato).
* **FAB (Floating Action Button):** Tasto "+" flottante in basso a destra per caricare un nuovo Meme (pagando la fee).

---

## Schema Logico dei Dati (Database Completo)

### 1. Core Utenti
| Tabella | Campi Chiave |
| :--- | :--- |
| **`users`** | `id`, `name`, `email`, `password`, `role` (admin/trader), `cfu_balance` (DECIMAL), `last_daily_bonus_at`. |

### 2. Core Mercato
| Tabella | Campi Chiave |
| :--- | :--- |
| **`categories`** | `id`, `name`, `slug`. |
| **`memes`** | `id`, `creator_id` (FK), `category_id` (FK), `title`, `image_path`, `current_price`, `total_shares`, `status`, `created_at`. |
| **`price_histories`** | `id`, `meme_id` (FK), `price`, `recorded_at` (usato per disegnare i grafici). |

### 3. Finanza & Transazioni
| Tabella | Campi Chiave |
| :--- | :--- |
| **`portfolios`** | `id`, `user_id` (FK), `meme_id` (FK), `quantity`, `avg_buy_price` (essenziale per calcolare il P&L %). |
| **`transactions`** | `id`, `user_id` (FK), `meme_id` (FK), `type` (buy, sell, ipo_fee, bonus), `quantity`, `price_per_share`, `fee_amount`, `total_amount`, `executed_at`. |

### 4. Utility
| Tabella | Campi Chiave |
| :--- | :--- |
| **`global_settings`** | `key`, `value`. (Es. `listing_fee`, `tax_rate`). |
| **`watchlists`** | `id`, `user_id` (FK), `meme_id` (FK). |
| **`notifications`** | `id`, `user_id` (FK) nullable, `title`, `message`, `is_read` nullable. |


## Note
* Registrazione possibile solo con email istituzionale (con OTP)
* Le notifiche con `user_id` nullo sono intese per tutti gli utenti