@echo off
echo 🗄️ Setting up Railway MySQL Database Configuration...
echo.

echo Setting Laravel environment variables for Railway MySQL...

railway variables set DB_CONNECTION=mysql
railway variables set DB_HOST="${{RAILWAY_PRIVATE_DOMAIN}}"
railway variables set DB_PORT=3306
railway variables set DB_DATABASE=railway
railway variables set DB_USERNAME=root
railway variables set DB_PASSWORD="${{MYSQL_ROOT_PASSWORD}}"

echo.
echo ✅ MySQL database configuration complete!
echo.
echo Your Laravel app will now use Railway's MySQL database with:
echo - Host: Railway Private Domain (internal)
echo - Port: 3306
echo - Database: railway
echo - User: root
echo - Password: Auto-generated secure password
echo.
echo 🚀 Deploy your app: railway up
echo 📋 Check logs: railway logs
echo.
pause
