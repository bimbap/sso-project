@echo off
echo Starting Laravel SSO Application...
echo.

echo Checking if database exists...
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS project_sso;"

echo.
echo Running migrations...
php artisan migrate

echo.
echo Starting Laravel development server...
echo Server will be available at: http://localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo.

php artisan serve
