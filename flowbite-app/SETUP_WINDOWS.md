# Setup Laravel su Windows con XAMPP

## 1. Software da installare

| Software | Link Download |
|----------|---------------|
| **XAMPP** | https://www.apachefriends.org/ |
| **Composer** | https://getcomposer.org/download/ |
| **Node.js** (LTS) | https://nodejs.org/ |

## 2. Configurazione PATH

Se PHP non viene riconosciuto da PowerShell, aggiungi `C:\xampp\php` alle variabili d'ambiente di Windows:

1. Vai su **Impostazioni Windows** → **Sistema** → **Informazioni** → **Impostazioni di sistema avanzate**
2. Clicca **Variabili d'ambiente**
3. In "Path" (variabili di sistema) aggiungi: `C:\xampp\php`

## 3. Verifica installazioni

Apri PowerShell e verifica:

```powershell
php -v
composer -v
node -v
npm -v
```

## 4. Setup progetto

```powershell
# Vai alla cartella del progetto
cd C:\path_to_project\

# Installa dipendenze PHP
composer install

# Installa dipendenze Node.js
npm install

# Copia il file .env (se non esiste)
copy .env.example .env

# Genera la chiave dell'app (se necessario)
php artisan key:generate
```

## 5. Configurazione Database

1. Avvia **XAMPP** → Start **MySQL**
2. Apri **phpMyAdmin** (`http://localhost/phpmyadmin`)
3. Crea un nuovo database chiamato: `flowbite_app`
4. Verifica che il file `.env` abbia queste impostazioni:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flowbite_app
DB_USERNAME=root
DB_PASSWORD=
```

## 6. Migrazioni

```powershell
php artisan migrate
```

## 7. Avvio server

Sono necessari **2 terminali** PowerShell aperti contemporaneamente:

### Terminale - Server Laravel

```powershell
cd C:\path_to_project\
composer run dev
```

## 8. Accedi all'applicazione

Apri il browser e vai su: **http://localhost:8000**

---

## Comandi utili

| Comando | Descrizione |
|---------|-------------|
| `composer run dev` | Avvia il server Laravel e Vite in modalità sviluppo |
| `php artisan serve` | Avvia il server Laravel |
| `npm run dev` | Avvia Vite in modalità sviluppo |
| `npm run build` | Compila gli asset per produzione |
| `php artisan migrate` | Esegue le migrazioni del database |
| `php artisan migrate:fresh` | Ricrea tutte le tabelle (cancella i dati!) |
| `php artisan cache:clear` | Pulisce la cache dell'applicazione |
| `php artisan config:clear` | Pulisce la cache della configurazione |

## Troubleshooting

### Errore "Connection refused" al database
- Verifica che MySQL sia avviato in XAMPP
- Controlla che il database `flowbite_app` esista in phpMyAdmin
- Verifica le credenziali nel file `.env`

### PHP non riconosciuto
- Aggiungi `C:\xampp\php` al PATH di Windows
- Riavvia PowerShell dopo aver modificato il PATH

### Errore permessi
- Esegui PowerShell come amministratore
