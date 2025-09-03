# Railway MySQL Configuration - Exact Setup

Based on your Railway MySQL service, here are the exact environment variables to use:

## Railway MySQL Environment Variables

Set these in your Railway web app service (not in the MySQL service):

```bash
# Database Configuration - Railway MySQL
DB_CONNECTION=mysql
DB_HOST=${{RAILWAY_PRIVATE_DOMAIN}}
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=${{MYSQL_ROOT_PASSWORD}}

# Alternative: Use specific MySQL variables
# DB_HOST=${{MYSQLHOST}}
# DB_PORT=${{MYSQLPORT}}
# DB_DATABASE=${{MYSQL_DATABASE}}
# DB_USERNAME=${{MYSQLUSER}}
```

## How Railway MySQL Variables Work

Railway automatically provides these variables when you add a MySQL service:

- `${{RAILWAY_PRIVATE_DOMAIN}}` - Internal hostname for MySQL service
- `${{MYSQL_ROOT_PASSWORD}}` - Auto-generated secure password
- `${{MYSQLHOST}}` - Alternative hostname variable
- `${{MYSQLPORT}}` - MySQL port (usually 3306)
- `${{MYSQL_DATABASE}}` - Database name (usually 'railway')
- `${{MYSQLUSER}}` - Database username (usually 'root')

## Complete Laravel Environment Variables for Railway MySQL

```bash
# Application
APP_NAME=Laravel SSO
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

# Database - Railway MySQL
DB_CONNECTION=mysql
DB_HOST=${{RAILWAY_PRIVATE_DOMAIN}}
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=${{MYSQL_ROOT_PASSWORD}}

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=https://your-app-name.up.railway.app/auth/google/callback
```

## Railway CLI Commands for MySQL Setup

```bash
# Add MySQL service to your project
railway add mysql

# Set database variables (Railway will auto-link MySQL variables)
railway variables set DB_CONNECTION=mysql
railway variables set DB_HOST='${{RAILWAY_PRIVATE_DOMAIN}}'
railway variables set DB_PORT=3306
railway variables set DB_DATABASE=railway
railway variables set DB_USERNAME=root
railway variables set DB_PASSWORD='${{MYSQL_ROOT_PASSWORD}}'

# Deploy with MySQL
railway up
```

## Important Notes

1. **No Manual Connection Strings**: Railway handles MySQL connections automatically
2. **Private Domain**: Use `${{RAILWAY_PRIVATE_DOMAIN}}` for internal service communication
3. **Auto-Generated Password**: Railway creates secure passwords automatically
4. **Database Creation**: Railway creates the 'railway' database by default
5. **Migrations**: Your `railway-start.sh` script will run `php artisan migrate` automatically

## Verification

After deployment, check your app logs:
```bash
railway logs
```

Look for successful database connection messages and migration completion.

Your MySQL database is ready to use with these exact variable configurations!
