@echo off
echo 🔧 Railway Registry Error Troubleshooting
echo ========================================
echo.
echo The error you're seeing is related to Railway's container registry in Asia-Southeast region.
echo Let's try several solutions:
echo.

echo 1️⃣ Clearing Docker build cache...
railway build --clear-cache

echo.
echo 2️⃣ Retrying deployment with different strategy...
railway up --detach

echo.
echo 3️⃣ If that fails, trying with legacy buildkit...
railway variables set DOCKER_BUILDKIT=0
railway up

echo.
echo 4️⃣ Alternative: Force rebuild from scratch...
railway service --clean

echo.
echo 🔍 If all fails, checking Railway status...
echo Visit: https://status.railway.app
echo.
echo 💡 Alternative solutions:
echo - Wait 10-15 minutes and retry (registry issues are often temporary)
echo - Try deploying from a different region
echo - Contact Railway support if issue persists
echo.
pause
