@echo off
echo 🚀 Alternative Deployment: Switch to Nixpacks
echo ==============================================
echo.
echo Railway registry is having issues. Let's try Nixpacks instead of Docker.
echo Nixpacks is Railway's native build system - often more reliable.
echo.

echo 1️⃣ Backing up Dockerfile...
if exist Dockerfile (
    copy Dockerfile Dockerfile.backup
    echo ✅ Dockerfile backed up as Dockerfile.backup
) else (
    echo ⚠️ No Dockerfile found to backup
)

echo.
echo 2️⃣ Temporarily renaming Dockerfile...
if exist Dockerfile (
    ren Dockerfile Dockerfile.disabled
    echo ✅ Dockerfile renamed to Dockerfile.disabled
)

echo.
echo 3️⃣ Using nixpacks.toml configuration...
if exist nixpacks.toml (
    echo ✅ nixpacks.toml found and ready
) else (
    echo ❌ nixpacks.toml not found! Please ensure it exists.
    pause
    exit /b 1
)

echo.
echo 4️⃣ Deploying with Nixpacks...
railway up

echo.
echo 🎉 Deployment complete!
echo.
echo If successful, your app is now running with Nixpacks instead of Docker.
echo This should avoid the registry issues you were experiencing.
echo.
echo To switch back to Docker later:
echo 1. Delete nixpacks.toml
echo 2. Rename Dockerfile.disabled back to Dockerfile
echo 3. Run: railway up
echo.
pause
