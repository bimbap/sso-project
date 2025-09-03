@echo off
REM Railway Environment Variables Configuration Script for Windows
REM This script sets up all required environment variables for Railway deployment

echo 🚀 Setting up Railway Environment Variables...

REM Check if Railway CLI is installed
where railway >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ❌ Railway CLI not found. Installing...
    npm install -g @railway/cli
)

REM Login to Railway
echo 🔑 Please login to Railway...
railway login

REM Create new Railway project
echo 📦 Creating Railway project...
railway init

REM Set production environment variables
echo ⚙️ Setting environment variables...

REM Core Laravel Configuration
railway variables set APP_NAME="Laravel SSO"
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false

REM Generate APP_KEY (you'll need to run php artisan key:generate --show separately)
echo 🔐 Please generate your APP_KEY by running: php artisan key:generate --show
echo Then run: railway variables set APP_KEY="your-generated-key"
pause

REM Database Configuration (SQLite - simpler setup)
railway variables set DB_CONNECTION=sqlite
railway variables set DB_DATABASE="/app/storage/database/database.sqlite"

REM Session Configuration
railway variables set SESSION_DRIVER=file
railway variables set SESSION_LIFETIME=120
railway variables set SESSION_ENCRYPT=false
railway variables set SESSION_PATH="/"
railway variables set SESSION_DOMAIN=""

REM Cache Configuration
railway variables set CACHE_DRIVER=file

REM Google OAuth Placeholders
echo 🔴 IMPORTANT: You need to set these Google OAuth variables manually:
echo    railway variables set GOOGLE_CLIENT_ID="your-client-id"
echo    railway variables set GOOGLE_CLIENT_SECRET="your-client-secret"
echo    railway variables set GOOGLE_REDIRECT_URI="https://your-domain.railway.app/auth/google/callback"

REM Ask about MySQL
set /p mysql_choice="🗄️ Do you want to add MySQL database instead of SQLite? (y/n): "
if /i "%mysql_choice%"=="y" (
    echo Adding MySQL service...
    railway add mysql

    echo ⏳ Waiting for MySQL to initialize...
    timeout /t 30

    REM Update database configuration for MySQL
    railway variables set DB_CONNECTION=mysql
    railway variables set DB_HOST="${{MYSQL_HOST}}"
    railway variables set DB_PORT="${{MYSQL_PORT}}"
    railway variables set DB_DATABASE="${{MYSQL_DATABASE}}"
    railway variables set DB_USERNAME="${{MYSQL_USERNAME}}"
    railway variables set DB_PASSWORD="${{MYSQL_PASSWORD}}"
)

REM Get the app URL after deployment
echo 🚀 Deploying to Railway...
railway up

echo ✅ Railway configuration complete!
echo 📱 Check your Railway dashboard for your app URL
echo.
echo 🔴 Don't forget to:
echo 1. Set up Google OAuth credentials in Google Cloud Console
echo 2. Add your Railway domain to Google OAuth redirect URIs
echo 3. Set the GOOGLE_* environment variables using the commands shown above
echo 4. Set your APP_URL: railway variables set APP_URL="https://your-actual-domain.railway.app"

pause
