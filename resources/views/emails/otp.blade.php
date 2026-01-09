<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #1e293b;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
            color: #cbd5e1;
        }
        .otp-box {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #ffffff;
            font-family: 'Courier New', monospace;
        }
        .otp-label {
            font-size: 12px;
            color: #e0e7ff;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .expiry {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            color: #64748b;
            font-size: 12px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #334155;
        }
        .warning {
            background-color: #431407;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📈 AlmaStreet</div>
        </div>

        <div class="greeting">
            Ciao {{ $userName }},
        </div>

        <div class="content">
            <p>Benvenuto su <strong>AlmaStreet</strong> - Il Mercato Azionario Accademico!</p>
            <p>Per completare la tua registrazione e iniziare a fare trading, usa il codice di verifica qui sotto:</p>
        </div>

        <div class="otp-box">
            <div class="otp-label">Il tuo Codice di Verifica</div>
            <div class="otp-code">{{ $otpCode }}</div>
        </div>

        <div class="expiry">
            ⏰ Questo codice scadrà tra <strong>10 minuti</strong>
        </div>

        <div class="warning">
            <strong>⚠️ Avviso di Sicurezza:</strong> Non condividere mai questo codice con nessuno. Lo staff di AlmaStreet non ti chiederà mai il tuo codice di verifica.
        </div>

        <div class="footer">
            <p>Se non hai richiesto questo codice, ignora questa email.</p>
            <p>&copy; {{ date('Y') }} AlmaStreet - Mercato Azionario Accademico. Tutti i diritti riservati.</p>
        </div>
    </div>
</body>
</html>
