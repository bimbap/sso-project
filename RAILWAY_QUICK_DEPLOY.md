# Simple Railway Deployment - Configuration as Code

Instead of following complex tutorials, just run one of these commands:

## 🚀 Quick Deploy Options

### Option 1: Windows Users
```cmd
railway-setup.bat
```

### Option 2: Node.js/npm Users  
```bash
npm run railway:setup
```

### Option 3: Bash/Linux/Mac Users
```bash
chmod +x railway-setup.sh
./railway-setup.sh
```

## What These Scripts Do Automatically:

✅ **Install Railway CLI** (if needed)  
✅ **Login to Railway** (prompts you)  
✅ **Create Railway project**  
✅ **Set all environment variables** automatically  
✅ **Generate Laravel APP_KEY**  
✅ **Configure database** (SQLite by default, MySQL optional)  
✅ **Deploy your app**  
✅ **Get your live URL**  

## After Running the Script:

You only need to do these 3 things manually:

### 1. Get Google OAuth Credentials
- Go to [Google Cloud Console](https://console.cloud.google.com)
- Create OAuth 2.0 Client ID
- Copy Client ID and Client Secret

### 2. Set Google OAuth Variables
```bash
railway variables set GOOGLE_CLIENT_ID="your-client-id-here"
railway variables set GOOGLE_CLIENT_SECRET="your-client-secret-here"  
railway variables set GOOGLE_REDIRECT_URI="https://your-app.railway.app/auth/google/callback"
```

### 3. Update Google OAuth Redirect URI
- In Google Cloud Console, add your Railway URL as redirect URI
- Format: `https://your-app.railway.app/auth/google/callback`

## That's It! 

Your app will be live at: `https://your-app-name.railway.app`

## Useful Commands After Deployment:

```bash
railway logs          # View app logs
railway status        # Check deployment status  
railway variables     # List all environment variables
railway open          # Open your app in browser
```

## Files Created for You:

- ✅ `railway.toml` - Railway configuration
- ✅ `Dockerfile` - Already optimized for Railway  
- ✅ `railway.yml` - Service configuration with health checks
- ✅ `railway-start.sh` - Startup script with migrations

## Troubleshooting:

**If deployment fails:** Run `railway logs` to see what went wrong  
**If app doesn't load:** Check that all environment variables are set  
**If OAuth fails:** Verify Google Cloud Console redirect URI matches your Railway URL
