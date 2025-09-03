# Complete Railway Deployment Guide for Laravel SSO Application

This comprehensive guide will walk you through deploying your Laravel SSO application to Railway and obtaining your live application URL.

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Railway Account Setup](#railway-account-setup)
3. [Project Configuration](#project-configuration)
4. [Environment Variables Setup](#environment-variables-setup)
5. [Database Configuration](#database-configuration)
6. [Google OAuth Configuration](#google-oauth-configuration)
7. [Deployment Process](#deployment-process)
8. [Obtaining Your Live URL](#obtaining-your-live-url)
9. [Health Check and Monitoring](#health-check-and-monitoring)
10. [Troubleshooting](#troubleshooting)

## Prerequisites

Before starting, ensure you have:
- GitHub account with your Laravel project repository
- Google Cloud Console account (for OAuth)
- Railway account (we'll create this)
- Your Laravel application working locally

## Railway Account Setup

### Step 1: Create Railway Account
1. Visit [railway.app](https://railway.app)
2. Click "Sign up" and use your GitHub account to sign up
3. Complete the account verification process

### Step 2: Connect GitHub Repository
1. In your Railway dashboard, click "New Project"
2. Select "Deploy from GitHub repo"
3. Choose your Laravel SSO project repository
4. Click "Deploy Now"

## Project Configuration

Your project already includes the necessary Railway configuration files:

### Files Created for Railway:
- `Dockerfile` - Container configuration optimized for Railway
- `railway.yml` - Railway service configuration with health checks
- `railway-start.sh` - Startup script for database migrations and optimization
- `.docker/apache.conf` - Apache web server configuration

## Environment Variables Setup

### Step 1: Access Railway Environment Variables
1. In your Railway project dashboard, go to the "Variables" tab
2. Add the following environment variables one by one:

### Required Laravel Environment Variables:

```bash
# Application Configuration
APP_NAME="Laravel SSO"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

# Database Configuration (Option 1: Railway MySQL)
DB_CONNECTION=mysql
DB_HOST=YOUR_RAILWAY_MYSQL_HOST
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=YOUR_RAILWAY_MYSQL_PASSWORD

# Database Configuration (Option 2: SQLite - Simpler)
DB_CONNECTION=sqlite
DB_DATABASE=/app/storage/database/database.sqlite

# Session Configuration
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=

# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=https://your-app-name.up.railway.app/auth/google/callback

# Cache Configuration
CACHE_DRIVER=file

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### Step 2: Generate Laravel Application Key
To generate your `APP_KEY`:
1. Run locally: `php artisan key:generate --show`
2. Copy the generated key (including `base64:` prefix)
3. Add it to Railway as `APP_KEY`

## Database Configuration

### Option 1: Railway MySQL Database (Recommended for Production)

1. In your Railway project, click "New" → "Database" → "Add MySQL"
2. Wait for the database to be provisioned
3. Go to your MySQL service and click "Variables" tab
4. Copy these values to your main app's environment variables:
   - `MYSQL_HOST` → Use as `DB_HOST`
   - `MYSQL_PORT` → Use as `DB_PORT` (usually 3306)
   - `MYSQL_DATABASE` → Use as `DB_DATABASE` (usually 'railway')
   - `MYSQL_ROOT_PASSWORD` → Use as `DB_PASSWORD`
   - Set `DB_USERNAME=root`
   - Set `DB_CONNECTION=mysql`

### Option 2: SQLite Database (Simpler Setup)

If you prefer SQLite for simplicity:
1. Set `DB_CONNECTION=sqlite`
2. Set `DB_DATABASE=/app/storage/database/database.sqlite`
3. Remove or leave empty: `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`

## Google OAuth Configuration

### Step 1: Create Google OAuth Credentials
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Go to "Credentials" → "Create Credentials" → "OAuth 2.0 Client IDs"
5. Choose "Web application"
6. Add authorized redirect URIs:
   - `http://localhost:8000/auth/google/callback` (for local development)
   - `https://your-app-name.up.railway.app/auth/google/callback` (for production)

### Step 2: Configure OAuth in Railway
1. Copy your Google Client ID and Client Secret
2. Add them to Railway environment variables:
   - `GOOGLE_CLIENT_ID=your-google-client-id`
   - `GOOGLE_CLIENT_SECRET=your-google-client-secret`
   - `GOOGLE_REDIRECT_URI=https://your-app-name.up.railway.app/auth/google/callback`

## Deployment Process

### Step 1: Trigger Deployment
1. Railway automatically deploys when you push to your main branch
2. Or manually trigger deployment from Railway dashboard
3. Click "Deploy" in your Railway project

### Step 2: Monitor Deployment
1. Watch the build logs in Railway dashboard
2. The deployment process includes:
   - Building Docker container
   - Installing PHP dependencies
   - Running database migrations
   - Starting Apache server

### Step 3: Health Check Verification
Your app includes a health check endpoint at `/health` that returns:
```json
{
  "status": "ok",
  "app": "Laravel SSO",
  "version": "1.0.0",
  "database": "connected",
  "timestamp": "2024-01-01T12:00:00Z"
}
```

## Obtaining Your Live URL

### Step 1: Find Your Railway URL
1. In your Railway project dashboard
2. Go to "Settings" tab
3. Look for "Domains" section
4. Your default URL will be: `https://your-app-name.up.railway.app`

### Step 2: Custom Domain (Optional)
1. In the "Domains" section, click "Custom Domain"
2. Enter your domain name
3. Configure DNS records as instructed
4. Update your environment variables with the new domain

### Step 3: Update Google OAuth
1. Go back to Google Cloud Console
2. Update your OAuth redirect URI with the new Railway URL
3. Redeploy your Railway application to pick up changes

## Health Check and Monitoring

### Railway Health Check
Railway uses the `/health` endpoint to monitor your application:
- **URL**: `https://your-app-name.up.railway.app/health`
- **Method**: GET
- **Expected Response**: 200 OK with JSON status

### Application Monitoring
1. Check Railway dashboard for:
   - Application logs
   - CPU and memory usage
   - Request metrics
   - Error rates

### Testing Your Deployment
1. Visit your Railway URL
2. Test the welcome page loads
3. Test Google OAuth login
4. Verify user dashboard access
5. Check admin functionality (if applicable)

## Troubleshooting

### Common Issues and Solutions

#### Issue: "500 Internal Server Error"
**Solution**: 
1. Check Railway logs for detailed error
2. Verify `APP_KEY` is set correctly
3. Ensure database connection is working
4. Check file permissions are set correctly

#### Issue: Google OAuth "redirect_uri_mismatch"
**Solution**:
1. Update Google Cloud Console with correct Railway URL
2. Verify `GOOGLE_REDIRECT_URI` environment variable
3. Ensure URL matches exactly (including https://)

#### Issue: Database Connection Failed
**Solution**:
1. Verify database environment variables
2. Check if Railway MySQL service is running
3. Test database connectivity from logs
4. Consider switching to SQLite for simplicity

#### Issue: "Application Key Not Set"
**Solution**:
1. Generate new key: `php artisan key:generate --show`
2. Add to Railway environment variables as `APP_KEY`
3. Include the `base64:` prefix

#### Issue: Assets Not Loading (CSS/JS)
**Solution**:
1. Check `APP_URL` environment variable
2. Ensure it matches your Railway domain exactly
3. Run `php artisan config:cache` (handled by startup script)

### Useful Railway Commands

Check application logs:
```bash
# View recent logs
railway logs

# Follow logs in real-time
railway logs --follow
```

### Support Resources

- **Railway Documentation**: [docs.railway.app](https://docs.railway.app)
- **Laravel Documentation**: [laravel.com/docs](https://laravel.com/docs)
- **GitHub Repository**: Your project repository for issues
- **Railway Discord**: Community support

## Final Checklist

Before going live, verify:
- [ ] All environment variables are set correctly
- [ ] Google OAuth is configured with Railway URL
- [ ] Database is connected and migrations ran
- [ ] Health check endpoint returns 200 OK
- [ ] Welcome page loads correctly
- [ ] User authentication works
- [ ] Admin dashboard accessible (if applicable)
- [ ] All routes function properly

## Your Railway App URL

Once deployed, your application will be available at:
**`https://your-actual-app-name.up.railway.app`**

This URL will be generated automatically by Railway and shown in your project dashboard under the "Settings" → "Domains" section.

---

**Need Help?** Check the Railway dashboard logs for detailed error messages, or refer to the troubleshooting section above.
