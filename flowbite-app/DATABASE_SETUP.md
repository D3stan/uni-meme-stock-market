# Setup Database & Meme Images - AlmaStreet

## Prerequisiti

- PHP >= 8.2
- Composer
- Node.js >= 18 e npm
- SQLite (o MySQL opzionale)

## 1. Installare le Dipendenze

Prima di tutto, installa le dipendenze PHP e Node.js:

```bash
cd flowbite-app

# Dipendenze PHP
composer install

# Dipendenze Node.js (Tailwind, Vite, Flowbite)
npm install
```

> **Nota:** Se hai problemi con `zip extension`, abilita l'estensione nel tuo `php.ini`:
> ```ini
> extension=zip
> ```

## 2. Configurare il Database

Crea il file `.env` copiando da `.env.example`:

```bash
copy .env.example .env   # Windows
# oppure: cp .env.example .env   # Mac/Linux

php artisan key:generate
```

### Configurazione MySQL

Se preferisci MySQL, lascia il `.env` così:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flowbite_app
DB_USERNAME=root
DB_PASSWORD=
```

## 3. Eseguire Migrazioni e Seeder

```bash
php artisan migrate:fresh --seed
```

Questo creerà tutte le tabelle e popolerà il database con:

### Utenti Creati

| ID | Nome | Email | Ruolo | Saldo CFU | Password |
|----|------|-------|-------|-----------|----------|
| 1 | Rettore Admin | admin@unibo.it | admin | 10,000 | password |
| 2 | Marco Rossi | marco.rossi@studio.unibo.it | trader | 250 | password |
| 3 | Giulia Bianchi | giulia.bianchi@studio.unibo.it | trader | 180 | password |
| 4 | Alessandro Verdi | alessandro.verdi@studio.unibo.it | trader | 520 | password |
| 5 | Sofia Romano | sofia.romano@studio.unibo.it | trader | 95 | password |
| 6 | Luca Ferrari | luca.ferrari@studio.unibo.it | trader | 100 | password |

### Meme Creati

| ID | Ticker | Titolo | Creatore | Status | Prezzo | Azioni |
|----|--------|--------|----------|--------|--------|--------|
| 1 | $STONK | Stonks Only Go Up | Marco (2) | approved | 3.50 | 25 |
| 2 | $ESAME | Esame alle 8 di mattina | Giulia (3) | approved | 1.50 | 20 |
| 3 | $PRESSF | Press F to Pay Respects | Alessandro (4) | approved | 7.00 | 10 |
| 4 | $HODL | HODL Forever | Marco (2) | approved | 2.10 | 30 |
| 5 | $ULTIMA | Quando il prof dice "ultima domanda" | Sofia (5) | **pending** | 1.00 | 0 |

## 4. Aggiungere le Immagini dei Meme

Le immagini vanno inserite nella cartella `storage/memes/` seguendo questa struttura:

```
flowbite-app/
└── storage/
    └── memes/
        ├── 2/                    # Cartella utente Marco (id: 2)
        │   ├── meme1.jpg         # $STONK
        │   └── meme4.jpg         # $HODL
        ├── 3/                    # Cartella utente Giulia (id: 3)
        │   └── meme2.jpg         # $ESAME
        ├── 4/                    # Cartella utente Alessandro (id: 4)
        │   └── meme3.jpg         # $PRESSF
        └── 5/                    # Cartella utente Sofia (id: 5)
            └── meme5.jpg         # $ULTIMA
```

### Passaggi:

1. **Crea le cartelle** per ogni utente:
   ```bash
   # Windows PowerShell
   mkdir storage\memes\2, storage\memes\3, storage\memes\4, storage\memes\5
   
   # Mac/Linux
   mkdir -p storage/memes/{2,3,4,5}
   ```

2. **Copia le tue 5 immagini** rinominandole:
   - `meme1.jpg` → `storage/memes/2/meme1.jpg` (Stonks)
   - `meme2.jpg` → `storage/memes/3/meme2.jpg` (Esame)
   - `meme3.jpg` → `storage/memes/4/meme3.jpg` (Press F)
   - `meme4.jpg` → `storage/memes/2/meme4.jpg` (HODL)
   - `meme5.jpg` → `storage/memes/5/meme5.jpg` (Ultima domanda)

## 5. Creare i Symbolic Links (OBBLIGATORIO)

Per servire le immagini pubblicamente, devi creare i symbolic links:

```bash
php artisan storage:link
```

> **Windows:** Potrebbe essere necessario eseguire il terminale come Amministratore.

Questo creerà:
- `public/storage` → `storage/app/public`
- `public/storage/memes` → `storage/memes`

## 6. Compilare gli Asset Frontend

Compila CSS (Tailwind) e JavaScript con Vite:

```bash
npm run build
```

> **Per sviluppo** puoi usare `npm run dev` che attiva l'hot reload.

## 7. Avviare il Server

```bash
php artisan serve
```

Il sito sarà disponibile su: **http://127.0.0.1:8000**

### Credenziali di Test

| Email | Password | Ruolo |
|-------|----------|-------|
| admin@unibo.it | password | Admin |
| marco.rossi@studio.unibo.it | password | Trader |
| giulia.bianchi@studio.unibo.it | password | Trader |

## 8. Verifica

Per verificare che tutto funzioni, puoi usare Tinker:

```bash
php artisan tinker
```

```php
// Conta utenti
App\Models\User::count(); // Dovrebbe essere 6

// Conta meme
App\Models\Meme::count(); // Dovrebbe essere 5

// Vedi meme approvati
App\Models\Meme::approved()->get(['id', 'ticker', 'title', 'current_price']);

// Vedi portafogli
App\Models\Portfolio::with('meme:id,ticker')->get(['user_id', 'meme_id', 'quantity']);
```

## Riepilogo Comandi Rapido

```bash
# Setup completo in un colpo solo
cd flowbite-app
composer install
npm install
copy .env.example .env
php artisan key:generate
# Crea database/database.sqlite se usi SQLite
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan serve
```

## Struttura Database Completa

Le tabelle create sono:

- `users` - Utenti (admin e trader)
- `categories` - Categorie meme
- `memes` - Meme quotati
- `price_histories` - Storico prezzi
- `portfolios` - Posizioni utenti
- `transactions` - Storico transazioni
- `watchlists` - Watchlist utenti
- `notifications` - Notifiche
- `badges` - Badge disponibili
- `user_badges` - Badge ottenuti
- `dividend_histories` - Storico dividendi
- `market_communications` - Comunicazioni admin
- `admin_actions` - Log azioni admin
- `otp_verifications` - Codici OTP
- `global_settings` - Impostazioni globali
- `sessions` - Sessioni utente
- `cache` - Cache applicazione
- `jobs` - Code lavori

## Troubleshooting

### Errore "Class not found"
```bash
composer dump-autoload
```

### CSS non caricato / pagina bianca
```bash
npm run build
```

### Immagini meme non visibili
```bash
php artisan storage:link
```
