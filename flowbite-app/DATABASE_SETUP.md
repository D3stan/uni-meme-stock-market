# Setup Database & Meme Images - AlmaStreet

## 1. Installare le Dipendenze

Prima di eseguire le migrazioni, assicurati di aver installato le dipendenze:

```bash
cd flowbite-app
composer install
```

> **Nota:** Se hai problemi con `zip extension`, abilita l'estensione nel tuo `php.ini`:
> ```ini
> extension=zip
> ```

## 2. Configurare il Database

Crea il file `.env` se non esiste (copia da `.env.example`):

```bash
cp .env.example .env
php artisan key:generate
```

Il progetto usa SQLite di default. Se preferisci MySQL, modifica il `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=almastreet
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
   mkdir -p storage/memes/2
   mkdir -p storage/memes/3
   mkdir -p storage/memes/4
   mkdir -p storage/memes/5
   ```

2. **Copia le tue 5 immagini** rinominandole:
   - `meme1.jpg` → `storage/memes/2/meme1.jpg` (Stonks)
   - `meme2.jpg` → `storage/memes/3/meme2.jpg` (Esame)
   - `meme3.jpg` → `storage/memes/4/meme3.jpg` (Press F)
   - `meme4.jpg` → `storage/memes/2/meme4.jpg` (HODL)
   - `meme5.jpg` → `storage/memes/5/meme5.jpg` (Ultima domanda)

## 5. Creare il Symbolic Link (Opzionale)

Per servire le immagini pubblicamente:

```bash
php artisan storage:link
```

## 6. Verifica

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
