# Railway Environment Variables Quick Reference

Copy and paste these environment variables into your Railway project's "Variables" tab.

## Essential Variables (Required)

```
APP_NAME=Laravel SSO
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
```

## Database Options

### Option 1: Railway MySQL (Recommended)
```
DB_CONNECTION=mysql
DB_HOST=${{RAILWAY_PRIVATE_DOMAIN}}
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=${{MYSQL_ROOT_PASSWORD}}
```

**OR use Railway's MySQL connection variables:**
```
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQL_DATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQL_ROOT_PASSWORD}}
```

### Option 2: SQLite (Simpler)
```
DB_CONNECTION=sqlite
DB_DATABASE=/app/storage/database/database.sqlite
```

## Google OAuth (Required for SSO)
```
GOOGLE_CLIENT_ID=your-google-client-id-from-console
GOOGLE_CLIENT_SECRET=your-google-client-secret-from-console
GOOGLE_REDIRECT_URI=https://your-app-name.up.railway.app/auth/google/callback
```

## Session & Cache
```
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
```

## Optional: Email Configuration
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

---

## How to Get Values:

### APP_KEY
Run locally: `php artisan key:generate --show`

### Railway MySQL Values
1. Add MySQL database to your Railway project
2. Go to MySQL service → Variables tab
3. Use Railway's automatic environment variables:
   - `${{RAILWAY_PRIVATE_DOMAIN}}` for DB_HOST
   - `${{MYSQL_ROOT_PASSWORD}}` for DB_PASSWORD
   - Or use specific variables like `${{MYSQLHOST}}`, `${{MYSQLPORT}}`, etc.

### Google OAuth Values
1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create OAuth 2.0 credentials
3. Add your Railway URL as redirect URI

### Your Railway URL
Check Railway dashboard → Settings → Domains section
