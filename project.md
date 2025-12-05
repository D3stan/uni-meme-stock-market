# Progetto #6: AlmaStreet (The Academic Stock Market)

## Visione del Progetto
Un'applicazione web gestionale che simula una borsa all'interno dell'ateneo. A differenza di un mercato tradizionale basato su ordini, le transazioni avvengono in modo istantaneo contro un protocollo **Automated Market Maker (AMM)**, che garantisce liquidità costante.
La valuta di scambio sono i **CFU (Credito Finanziario Universitario)**. L'obiettivo degli studenti è massimizzare il valore del proprio portafoglio speculando sulla popolarità dei contenuti (Meme) generati dalla community.

---

## Stack Tecnologico Scelto
* **Backend:** Framework **Laravel** (PHP). Verranno utilizzati:
    * **Eloquent ORM:** Per la gestione del database e delle relazioni.
    * **Migrations & Seeders:** Per la creazione dello schema e il popolamento iniziale di dati finti (utenti, meme, storico transazioni) necessari per la demo.
    * **Transazioni Atomiche:** Per garantire l'indivisibilità delle operazioni finanziarie.
* **Frontend:**
    * HTML5, CSS, **Javascript "Vanilla"** (senza framework come React/Vue/Angular)
    * Bootstrap / Flowbite
    * **TradingView Lightweight Charts** e **Chart.js** per i grafici.

---

## Dettagli Funzionali e Implementativi (32 Punti)

### 1. Design dell'Interfaccia Utente (Mobile First & UX) (4 punti)

L'interfaccia adotta un approccio **Mobile First** rigoroso, ottimizzato per l'utilizzo a una mano (**"Thumb-driven design"**). Lo stile visivo segue i canoni del **Modern Fintech** (pulizia, numeri grandi, contrasto elevato rosso/verde) unito all'estetica **Social** (immagini full-bleed, interazioni rapide).

È preferibile l'adozione di un **tema scuro (Dark Mode)** come default, per ridurre l'affaticamento visivo e allinearsi agli standard delle app di trading/crypto.

---

#### A. Navigazione Globale (Layout Shell)

La struttura cambia drasticamente in base al device per garantire la migliore ergonomia:

* **Mobile (Smartphone):**
    * **Bottom Navigation Bar:** Barra fissa in basso contenente 4/5 icone principali (**Market, Trade/Search, Portafoglio, Classifica, Profilo**). È la "zona sicura" per il pollice.
    * **Top Bar Contestuale:** Cambia in base alla pagina (contiene titolo, pulsante "Indietro" o azioni secondarie come le impostazioni).
* **Desktop/Tablet:**
    * La **Bottom Bar** diventa una **Top Navbar** classica, sfruttando lo spazio orizzontale per mostrare più dettagli nelle tabelle.

---

#### B. Marketplace (Home & Discovery)

Il punto di ingresso dell'utente, progettato per creare **FOMO (Fear Of Missing Out)** e interesse rapido.

* **Ticker "Marquee":** Striscia scorrevole in alto (sotto l'header) stile borsa, che mostra i top titoli per volatilità/volume in tempo reale.
* **Filtri Rapidi (Chips):** Pillole scorrevoli orizzontalmente per filtrare il feed: Tutti, Top Gainer, New Listing, High Risk.
* **Feed Titoli (Meme Card):**
    * Layout a colonna singola (**Instagram style**).
    * **Anatomia della Card:**
        * **Header:** Titolo Meme (es. "Surprised Pikachu") e Ticker (es. $PIKA).
        * **Body:** Immagine del Meme (**Aspect Ratio** con larghezza fissa ma altezza variabile in base all’altezza del meme), cliccabile per il dettaglio.
        * **Footer:** Prezzo attuale (**Font Monospace grande**) | Badge % 24h (Verde/Rosso acceso) | Tasto rapido **"Trade"** (Outline).

---

#### C. Trade Station (Pagina Operativa)

Il cuore dell'applicazione. Deve prevenire errori cognitivi e trasmettere sicurezza. Layout a blocchi verticali:

* **Blocco 1: Header Finanziario**
    * Prezzo attuale in grande evidenza.
    * Sotto-titolo con variazione assoluta (CFU) e percentuale. Il colore del testo cambia dinamicamente (**Verde/Rosso**).
* **Blocco 2: Visualizzazione Dati**
    * **Grafico (Chart.js/TradingView):** Interattivo, ma semplificato per mobile (nascondere assi/gridlines superflue su schermi piccoli).
    * **Meme:** Visualizzazione meme alternata al grafico alla pressione di un pulsante o tramite gesture (es. swipe per "girare" la card).
* **Blocco 3: Barra Azioni e Pop-up (Sticky Bottom)**
    * **Barra Fissa:** In basso alla pagina sono sempre visibili due pulsanti affiancati (50% width ciascuno):
        * `[ VENDI ]` (Rosso/Secondary)
        * `[ COMPRA ]` (Verde/Primary)
    * **Interazione Pop-up (Modal Bottom Sheet):**
        * Premendo uno dei due pulsanti, si apre un pannello in sovrimpressione (dal basso verso l'alto) specifico per l'azione scelta.
    * **Contenuto del Pop-up:**
        * **Header:** Titolo operazione (es. "Acquista $DOGE") e Saldo disponibile.
        * **Input Area:** Campo "**Quantità**" numerico centrale con focus automatico.
        * **Shortcuts:** Slider rapido con marker snap "**25%**, **50%**, **75%**, **MAX**" per auto-compilare la quantità.
        * **Riepilogo:** Dettaglio costi (Prezzo Stimato, Fee, Totale).
        * **Conferma:** Bottone finale "**Esegui Acquisto/Vendita**" (Stato caricamento con spinner durante API call).

---

#### D. Il Mio Portafoglio (Dashboard Personale)

Non una semplice lista, ma uno strumento di analisi.

* **Hero Section:**
    * **Net Worth Totale:** La cifra più grande della pagina.
    * **PNL Giornaliero:** Badge che indica quanto l'utente ha guadagnato/perso oggi rispetto a ieri.
    * **Chart Asset Allocation:** Grafico a Ciambella minimalista (Liquidità vs Investito).
* **Lista Asset (Compact View):**
    * Lista densa. Ogni riga contiene:
        * **Sx:** Miniatura tonda + Ticker.
        * **Centro:** Q.tà posseduta + Valore attuale.
        * **Dx:** PNL della posizione (in % e assoluto).
* **FAB (Floating Action Button):** Pulsante circolare flottante in basso a destra con icona `+` per "Listare un nuovo Meme" (**Create IPO**) che su Desktop diventa rettangolo molto arrotondato.

---

#### E. Feedback di Sistema e Stati (Micro-Interazioni)

Per elevare la **qualità percepita (Perceived Quality)** del progetto:

* **Skeleton Loading:** Durante il caricamento dati (**fetch API**), non usare semplici spinner, ma mostrare sagome grigie pulsanti (scheletri) della struttura della pagina. 

* **Toast Notifications:** Per gli esiti delle transazioni (es. "Ordine Eseguito: +10 $DOGE", "Errore: Saldo insufficiente"). Devono apparire come popup non invasivi in alto o in basso, sparendo dopo 3 secondi.
* **Modali di Conferma:** Per azioni distruttive (es. "Vendi tutto", "Cancella Account") o ad alto rischio (es. Slippage elevato rilevato).

### 2. Registrazione e Login (4 punti)
* **Verifica:** verifica dell'account istituzionale con codice di conferma tramite email.
* **Controllo dati inseriti:** verifica email, password forte (con conferma).
* **Bonus Matricola:** Il sistema prevede un meccanismo di incentivazione all'ingresso. Ogni nuovo utente riceve una dotazione iniziale di **100 CFU**.
* **Gestione account:** possibilita di cancellare / disattivare l'account.

### 3. Gestione Admin: "Il Rettorato" (8 punti - CRUD)
* **IPO Maker (Create):** L'Admin approva i meme proposti dagli utenti (che hanno pagato la Listing Fee), stabilendo il prezzo di lancio (base price) e il profilo di rischio (slope). Questo processo (Initial Public Offering) crea il primo record nello storico dei prezzi e rende il meme disponibile per il trading.
* **Sospensione Titoli (Update):** L'Admin può bloccare le contrattazioni su un titolo in caso di anomalie di mercato.
* **Monitoraggio (Read):** Dashboard con metriche globali (es. Totale Fee raccolte, Inflazione del server).
* **Delisting (Delete):** Soft delete dei meme "falliti" o obsoleti che non vengono scambiati da tempo.

* **Market Surveillance (Read):** Dashboard anti-frode dedicata che evidenzia indicatori chiave per il team amministrativo:
    * **Top Gainers/Losers:** Elenco dei titoli con le variazioni più estreme per identificare pump-and-dump sospetti.
    * **Whale Alert:** Segnalazione quando un singolo utente detiene >10% delle azioni in circolazione di un meme (possibilità di flaggare/monitorare il conto).
    * **Anomalie di Volume:** Grafici e alert per volumi anomali rispetto alla baseline storica.

* **Comunicazioni di Mercato (Update):** Funzionalità che permette all'Admin ("Rettorato") di pubblicare messaggi ufficiali visibili a tutti gli utenti:
    * **Messaggio del Rettore:** Campo per inserire testo breve (es. "Manutenzione server", "Chiusura borsa per festività").
    * **Ticker Globale:** Il messaggio appare come ticker scorrevole (banner) in tutte le pagine client fino a rimozione o scadenza.

### 4. Profilo Utente: "Il Portafoglio" (4 punti)
Una dashboard finanziaria personale, non un profilo social.
* **Asset Allocation:** Grafico a torta (Chart.js) che mostra la composizione del patrimonio (Liquidità vs Investimenti).
* **Valore Netto (Net Worth):** Indicatore calcolato in tempo reale: Saldo Liquido + (Azioni Possedute * Prezzo Attuale).
* **Storico Transazioni:** Tabella paginata con ogni movimento: data, tipo, prezzo unitario e fee pagate.
* **Verifica Copertura:** Appositi controlli di sistema monitorano la disponibilità economica prima di ogni transazione, impedendo operazioni se il saldo è insufficiente.
* **Personalizzazione:** profile picture, "stato", nickname.


### 5. Fruizione del Servizio: Trading Core (8 punti)

#### A. Meccanica di Proposta (Tassa di Quotazione)
Per proporre un nuovo meme, l'utente paga una **Listing Fee** (es. 20 CFU) non rimborsabile. Il meme viene quindi inserito in una coda di approvazione per il "Rettorato" (che non implica il suo bloccaggio, ma solo una notifica per gli admin di controllarlo asap) ma è fin da subito visibile a tutti gli utenti. Dopo un certo delay (solitamente il giorno dopo) è possibile iniziare il  trading del meme (questo crea hype per le persone che credono che il meme possa andare virale). Se il saldo è insufficiente, l'operazione viene bloccata. Questo processo a due fasi (pagamento + approvazione admin) disincentiva lo spam e garantisce la qualità dei contenuti sul mercato.

#### B. Algoritmo di Pricing (Linear Bonding Curve)
Per garantire un mercato dinamico ed evitare stalli di liquidità (problema tipico dei simulatori con pochi utenti), il sistema abbandona il matching "peer-to-peer" in favore di un modello **Automated Market Maker (AMM)** a crescita lineare. Gli utenti fanno trading contro il "Banco" (il Sistema), che garantisce liquidità immediata.

La formula utilizzata per il calcolo del prezzo istantaneo è:

$$P = P_{base} + (M \cdot S)$$

Dove:
* **$P$**: Prezzo corrente dell'azione.
* **$P_{base}$**: Prezzo base di quotazione (es. 1.00 CFU), impostato dal Rettorato.
* **$M$ (Slope)**: Coefficiente di volatilità (es. 0.1), impostato dal Rettorato. Determina di quanti CFU aumenta il prezzo per ogni singola azione emessa.
* **$S$ (Azioni in Circolazione)**: Numero totale di azioni *attualmente* in circolazione e detenute dagli utenti. **Questo valore è dinamico.**

**Meccanica di Compravendita (Mint & Burn):**
1.  **Acquisto (Minting):** Quando un utente compra, il sistema **conia (crea)** nuove azioni dal nulla e le assegna all'utente.
    * *Effetto:* Il numero di azioni ($S$) aumenta $\rightarrow$ Il prezzo sale matematicamente per l'acquirente successivo.
2.  **Vendita (Burning):** Quando un utente vende, il sistema ritira le azioni dal portafoglio utente e le **brucia (distrugge)** definitivamente.
    * *Effetto:* Il numero di azioni ($S$) diminuisce $\rightarrow$ Il prezzo scende.

**Vantaggi del Modello:**
* **Liquidità Infinita:** È sempre possibile vendere le proprie azioni e incassare CFU, non serve aspettare che ci sia un altro studente disposto a comprare.
* **Volatilità Garantita:** Anche con un numero ristretto di utenti (es. 20), il prezzo reagisce immediatamente alla domanda e all'offerta, premiando gli *early adopters* (chi compra quando il numero di azioni in circolazione è basso) e creando rischio reale per chi entra tardi.

#### Calcolo dello Slippage (Formula Integrale O(1))

**Problema:** La formula $P = P_{base} + (M \cdot S)$ fornisce il prezzo istantaneo, ma per ordini di $k$ azioni, ogni azione viene mintata a un prezzo crescente. Calcolare il costo con un loop è inefficiente e soggetto a errori di arrotondamento.

**Soluzione:** Si usa il calcolo integrale (area sotto la curva di prezzo) per ottenere il costo totale in un'operazione atomica O(1).

**Formula per l'Acquisto di $k$ Azioni:**

Partendo da una supply corrente $S$, il costo totale per acquistare $k$ azioni è:

$$\text{CostoTotale} = P_{base} \cdot k + \frac{M}{2} \cdot ((S+k)^2 - S^2)$$

**Derivazione:**
$$\text{CostoTotale} = \int_{S}^{S+k} (P_{base} + M \cdot s) \, ds = P_{base} \cdot k + M \cdot \left[\frac{s^2}{2}\right]_{S}^{S+k}$$

**Formula per la Vendita di $k$ Azioni:**

Per vendere $k$ azioni (con $S \geq k$), l'incasso totale è:

$$\text{IncassoTotale} = P_{base} \cdot k + \frac{M}{2} \cdot (S^2 - (S-k)^2)$$

**Implementazione nel Service Layer:**
* Queste formule vanno implementate come metodi atomici nel Service Layer (es. `TradingService`).
* Il calcolo deve essere eseguito all'interno di una transazione database per garantire consistenza in caso di concorrenza.
* Le fee vengono applicate **dopo** il calcolo del costo/incasso: `CostoFinale = CostoTotale * (1 + TaxRate)` per acquisto, `IncassoFinale = IncassoTotale * (1 - TaxRate)` per vendita.
* La supply viene aggiornata atomicamente: `circulating_supply = circulating_supply + k` per acquisti, `circulating_supply = circulating_supply - k` per vendite.

**Gestione della Concorrenza:**
* Si usano le transazioni database di Laravel con locking ottimistico o pessimistico (`FOR UPDATE`) per evitare race condition quando due utenti operano sullo stesso meme simultaneamente.
* Il valore di $S$ deve essere letto e aggiornato all'interno della stessa transazione atomica per garantire la correttezza del calcolo.


#### C. Compravendita e Commissioni
* **Fee di Segreteria:** Su ogni transazione viene trattenuta una percentuale (es. 2%). L'utente realizza un profitto solo se il prezzo di vendita è maggiore del prezzo d'acquisto più le fee. Questo meccanismo scoraggia lo "scalping" (compravendita frenetica per micro-guadagni).
* **Atomicità:** Il prelievo dei CFU dal saldo utente, l'accredito delle commissioni al sistema e l'assegnazione delle azioni avvengono in un unico blocco indivisibile. Se una qualsiasi di queste operazioni fallisce, l'intera transazione viene annullata, garantendo che non si perdano fondi o azioni.
* **Anteprima Ordine e Protezione Slippage:** Prima di eseguire definitivamente un ordine di acquisto o vendita, il sistema effettua una richiesta di preview al server che calcola il costo/incasso reale in base alla supply corrente. Se il prezzo è cambiato rispetto a quello visualizzato dall'utente (a causa di transazioni avvenute nel frattempo), viene mostrato un modal di conferma che evidenzia:
    * Il prezzo inizialmente visualizzato
    * Il prezzo attuale aggiornato
    * Il costo totale ricalcolato (con fee incluse)
    * La variazione percentuale (slippage)
    * L'utente deve confermare esplicitamente per procedere, oppure può annullare l'operazione
* Questo meccanismo garantisce trasparenza e previene sorprese per l'utente finale, assicurando che sia sempre consapevole del prezzo effettivo prima di completare la transazione.

#### D. Aggiornamento Dati (Strategia Tecnica)
Per mantenere i dati aggiornati in tempo reale, l'interfaccia utente adotta una strategia di **aggiornamenti periodici**:
* A intervalli regolari (es. ogni 5-10 secondi), l'applicazione contatta il server per ricevere le ultime variazioni di prezzo in un formato dati standard.
* Una volta ricevuti i dati, l'interfaccia aggiorna i valori e i grafici senza bisogno di ricaricare l'intera pagina.
* Nota operativa: la frequenza degli aggiornamenti può essere regolata dinamicamente per ottimizzare le performance, con meccanismi di salvaguardia per non sovraccaricare il sistema.

### 6. Effetto WOW (4 punti)

#### A. Gamification: "La Dean's List"
Per incentivare la competizione e l'uso continuativo dell'applicazione:
* **Leaderboard Globale:** Classifica pubblica degli studenti ordinata per *Net Worth* (Patrimonio Totale).
* **Badges Profilo:** Assegnazione automatica di medaglie visibili nel profilo utente al raggiungimento di milestone:
    * **Diamond Hands:** Mantiene un titolo in portafoglio per > 1 settimana senza venderlo.
    * **IPO Hunter:** Partecipa a 5 lanci di nuovi meme.
    * **Liquidator:** Raggiunge 0 CFU di saldo (Badge "Bancarotta").

#### B. Meccanica dei Dividendi (Holding Incentive)
Per simulare un mercato azionario reale e premiare il comportamento di lungo termine:
* **Stacco Cedola:** Ogni notte (implementabile tramite un job schedulato o tramite un evento applicativo al primo accesso del giorno) i meme che hanno mantenuto un trend positivo nelle ultime 24h distribuiscono un "Dividendo Accademico".
    * **Calcolo Dividendo:** Il sistema cattura uno snapshot della quantità di azioni in circolazione a un'ora prestabilita. Il dividendo (es. 1% del valore di mercato totale in quel momento) viene calcolato sul totale e poi ripartito in base allo snapshot, ottenendo un importo per azione.
    * **Erogazione:** L'importo (importo per azione * quantità posseduta) viene accreditato nel saldo CFU di ciascun azionista che deteneva azioni allo snapshot; l'operazione è registrata nello storico come transazione di tipo dividend.
* **Obiettivi:** Incentivare il mantenimento dei titoli, ridurre il turnover istantaneo e creare storyline di lungo periodo attorno ai meme più solidi.

#### C. Mercato Dinamico con Asset a Rischio Variabile
Per aumentare la profondità strategica, il parametro **Slope ($M$)** della Bonding Curve non è una costante globale, ma un attributo specifico di ogni meme, impostato globalmente dall'Admin e modificabili per ogni meme in qualunque momento.
* **Come funziona:** Questo permette di creare diverse classi di asset con profili di rischio differenti.
    * **Meme Stabili (Slope basso):** Titoli a bassa volatilità, simili a "blue chip", che crescono lentamente ma in modo costante. Attraggono investitori prudenti.
    * **Meme Speculativi (Slope alto):** Titoli ad alta volatilità, con un alto potenziale di guadagno (e di perdita). Attraggono trader che cercano il "pump" rapido.
* **Vantaggi:** Questa variabilità rende il mercato meno monotono e introduce un elemento di analisi fondamentale: gli utenti dovranno valutare non solo la popolarità di un meme, ma anche il suo profilo di rischio intrinseco.

---

## Schema Logico dei Dati (Database Completo)

### 1. Core Utenti
| Tabella | Campi Chiave |
| :--- | :--- |
| **`users`** | `id` (PK), `name`, `email`, `password`, `role` (admin/trader), `cfu_balance` (DECIMAL), `is_suspended` (boolean), `email_verified_at`, `last_daily_bonus_at`, `created_at`, `updated_at`, `cached_net_worth` (DECIMAL). |

### 2. Core Mercato
| Tabella | Campi Chiave |
| :--- | :--- |
| **`categories`** | `id` (PK), `name`, `slug`, `created_at`, `updated_at`. |
| **`memes`** | `id` (PK), `creator_id` (FK), `category_id` (FK), `title`, `image_path`, `base_price` (DECIMAL), `slope` (DECIMAL), `current_price` (DECIMAL, cache), `circulating_supply` (BIGINT UNSIGNED, dinamico), `status` (pending/approved/suspended), `approved_at`, `approved_by` (FK), `created_at`, `updated_at`, `deleted_at`. |
| **`price_histories`** | `id` (PK), `meme_id` (FK), `price`, `circulating_supply_snapshot`, `trigger_type` (buy/sell/ipo), `recorded_at`, `volume_24h` (DECIMAL), `pct_change_24h` (DECIMAL), INDEX(meme_id, recorded_at). |

### 3. Finanza & Transazioni
| Tabella | Campi Chiave |
| :--- | :--- |
| **`portfolios`** | `id` (PK), `user_id` (FK), `meme_id` (FK), `quantity`, `avg_buy_price` (DECIMAL), `created_at`, `updated_at`. |
| **`transactions`** | `id` (PK), `user_id` (FK), `meme_id` (FK, **nullable**), `type` (buy, sell, listing_fee, bonus, dividend), `quantity`, `price_per_share` DECIMAL(16, 4), `fee_amount`, `total_amount`, `cfu_balance_after`, `executed_at`. |

### 4. Utility
| Tabella | Campi Chiave |
| :--- | :--- |
| **`global_settings`** | `key` (PK), `value`. (Es. `listing_fee`, `tax_rate`). |
| **`watchlists`** | `id` (PK), `user_id` (FK), `meme_id` (FK), `created_at`, `updated_at`. |
| **`notifications`** | `id` (PK), `user_id` (FK) nullable, `title`, `message`, `is_read` nullable, `created_at`, `updated_at`. |

### 5. Tabelle Aggiuntive (Gamification, Audit, Dividendi)
| Tabella | Campi Chiave |
| :--- | :--- |
| **`badges`** | `id` (PK), `name`, `description`, `icon_path`, `created_at`, `updated_at`. |
| **`user_badges`** | `id` (PK), `user_id` (FK), `badge_id` (FK), `awarded_at`. |
| **`dividend_histories`** | `id` (PK), `meme_id` (FK), `amount_per_share`, `total_distributed`, `distributed_at`. |
| **`market_communications`** | `id` (PK), `admin_id` (FK), `message`, `is_active`, `expires_at`, `created_at`, `updated_at`. |
| **`admin_actions`** | `id` (PK), `admin_id` (FK), `action_type`, `target_id`, `target_type`, `reason`, `created_at`. |
| **`otp_verifications`** | `id` (PK), `email` (INDEX), `code_hash`, `expires_at`, `created_at`. |



## Note Strutturali e di Performance

*   **Precisione Numerica:** Tutti i campi di tipo DECIMAL che rappresentano valori monetari o parametri di calcolo (es. cfu_balance, current_price, base_price, slope) dovrebbero utilizzare una precisione e scala adeguate per evitare errori di arrotondamento, ad esempio DECIMAL(15, 5).
*   **Coerenza del Prezzo:** Il campo memes.current_price è una forma di denormalizzazione (cache) per ottimizzare le letture. Il suo valore viene ricalcolato e aggiornato ad ogni transazione (acquisto/vendita) basandosi sulla formula della bonding curve. La fonte di verità rimane sempre la formula P = P_base + (M * S).
*   **Aggiornamento avg_buy_price:** Il campo portfolios.avg_buy_price è cruciale per il calcolo del Profit & Loss. Viene aggiornato ad ogni acquisto con la seguente formula: new_avg = ((old_qty * old_avg) + (new_qty * new_price)) / (old_qty + new_qty).
*   **Constraint e Indici:** È fondamentale aggiungere UNIQUE constraints per prevenire dati duplicati, in particolare su portfolios(user_id, meme_id) e watchlists(user_id, meme_id). Inoltre, vanno creati indici (INDEX) su tutte le Foreign Keys e sui campi utilizzati frequentemente nelle query (es. memes.status, transactions.executed_at) per garantire performance ottimali.
* **Timestamps Standard Laravel:** Per coerenza con le best practice di Laravel e per facilitare il debugging, quasi tutte le tabelle dovrebbero includere i campi `created_at` e `updated_at`, gestiti automaticamente da Eloquent.
* **Concorrenza:** Le operazioni di trading devono essere protette da transazioni database con row-level locking (es. `SELECT ... FOR UPDATE` in Laravel) per garantire che il valore di `circulating_supply` sia consistente quando più utenti operano simultaneamente sullo stesso meme. Il pattern di implementazione prevede: (1) inizio transazione, (2) lock della riga del meme, (3) lettura della supply corrente, (4) calcolo del costo con formula integrale, (5) aggiornamento atomico della supply e del saldo utente, (6) commit. In caso di deadlock, il sistema effettua retry automatico.

## Note
* Registrazione possibile solo con email istituzionale (con OTP)
* Le notifiche con `user_id` nullo sono intese per tutti gli utenti
* Mettere meme vari nell'applicazione (badge stonks/not_stonks, animazioni meme, interazione input troll)
* Hashing monodirezionale per le password

## Note correttive
* Tabella di utility a parte per gli OTP dove poi vengono cancellate le righe una volta verificati