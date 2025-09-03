#!/bin/bash

# Railway Environment Variables Configuration Script
# This script sets up all required environment variables for Railway deployment

echo "🚀 Setting up Railway Environment Variables..."

# Check if Railway CLI is installed
if ! command -v railway &> /dev/null; then
    echo "❌ Railway CLI not found. Installing..."
    npm install -g @railway/cli
fi

# Login to Railway (if not already logged in)
echo "🔑 Please login to Railway..."
railway login

# Create new Railway project (if not exists)
echo "📦 Creating Railway project..."
railway init

# Set production environment variables
echo "⚙️ Setting environment variables..."

# Core Laravel Configuration
railway variables set APP_NAME="Laravel SSO"
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false
railway variables set APP_URL="https://$(railway status --json | jq -r '.deployments[0].url')"

# Generate and set APP_KEY
echo "🔐 Generating Laravel application key..."
APP_KEY=$(php artisan key:generate --show 2>/dev/null || echo "base64:$(openssl rand -base64 32)")
railway variables set APP_KEY="$APP_KEY"

# Database Configuration (SQLite - simpler setup)
railway variables set DB_CONNECTION=sqlite
railway variables set DB_DATABASE="/app/storage/database/database.sqlite"

# Session Configuration
railway variables set SESSION_DRIVER=file
railway variables set SESSION_LIFETIME=120
railway variables set SESSION_ENCRYPT=false
railway variables set SESSION_PATH="/"
railway variables set SESSION_DOMAIN=""

# Cache Configuration
railway variables set CACHE_DRIVER=file

# Google OAuth Placeholders (you need to fill these)
echo "🔴 IMPORTANT: You need to set these Google OAuth variables manually:"
echo "   railway variables set GOOGLE_CLIENT_ID='your-client-id'"
echo "   railway variables set GOOGLE_CLIENT_SECRET='your-client-secret'"
echo "   railway variables set GOOGLE_REDIRECT_URI='https://your-domain.railway.app/auth/google/callback'"

# Optional: Add MySQL database service
read -p "🗄️ Do you want to add MySQL database instead of SQLite? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Adding MySQL service..."
    railway add mysql

    # Wait for MySQL to be ready
    echo "⏳ Waiting for MySQL to initialize..."
    sleep 30

    # Update database configuration for MySQL
    railway variables set DB_CONNECTION=mysql
    railway variables set DB_HOST='${{MYSQL_HOST}}'
    railway variables set DB_PORT='${{MYSQL_PORT}}'
    railway variables set DB_DATABASE='${{MYSQL_DATABASE}}'
    railway variables set DB_USERNAME='${{MYSQL_USERNAME}}'
    railway variables set DB_PASSWORD='${{MYSQL_PASSWORD}}'

    # Remove SQLite config
    railway variables delete DB_DATABASE
fi

# Deploy the application
echo "🚀 Deploying to Railway..."
railway up

echo "✅ Railway configuration complete!"
echo "📱 Your app will be available at: https://$(railway status --json | jq -r '.deployments[0].url')"
echo ""
echo "🔴 Don't forget to:"
echo "1. Set up Google OAuth credentials in Google Cloud Console"
echo "2. Add your Railway domain to Google OAuth redirect URIs"
echo "3. Set the GOOGLE_* environment variables using the commands shown above"
