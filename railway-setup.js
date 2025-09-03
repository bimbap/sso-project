const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

console.log('🚀 Railway Configuration as Code Setup');
console.log('=====================================\n');

// Check if Railway CLI is installed
function checkRailwayCLI() {
    try {
        execSync('railway --version', { stdio: 'ignore' });
        console.log('✅ Railway CLI found');
        return true;
    } catch (error) {
        console.log('❌ Railway CLI not found. Installing...');
        try {
            execSync('npm install -g @railway/cli', { stdio: 'inherit' });
            console.log('✅ Railway CLI installed');
            return true;
        } catch (installError) {
            console.error('❌ Failed to install Railway CLI:', installError.message);
            return false;
        }
    }
}

// Generate Laravel APP_KEY
function generateAppKey() {
    try {
        const key = execSync('php artisan key:generate --show', { encoding: 'utf8' }).trim();
        console.log('🔐 Generated Laravel APP_KEY');
        return key;
    } catch (error) {
        // Fallback key generation
        const crypto = require('crypto');
        const key = 'base64:' + crypto.randomBytes(32).toString('base64');
        console.log('🔐 Generated fallback APP_KEY (run php artisan key:generate for proper key)');
        return key;
    }
}

// Setup Railway project
async function setupRailway() {
    try {
        console.log('\n📦 Setting up Railway project...');

        // Login to Railway
        console.log('🔑 Please login to Railway...');
        execSync('railway login', { stdio: 'inherit' });

        // Initialize project
        execSync('railway init', { stdio: 'inherit' });

        console.log('✅ Railway project initialized');
        return true;
    } catch (error) {
        console.error('❌ Railway setup failed:', error.message);
        return false;
    }
}

// Set environment variables with MySQL option
function setEnvironmentVariables() {
    console.log('\n⚙️ Setting environment variables...');

    const appKey = generateAppKey();

    // Ask user about database choice
    const readline = require('readline');
    const rl = readline.createInterface({
        input: process.stdin,
        output: process.stdout
    });

    return new Promise((resolve) => {
        rl.question('🗄️ Do you want to use MySQL database? (y/n): ', (answer) => {
            rl.close();

            let variables;

            if (answer.toLowerCase() === 'y' || answer.toLowerCase() === 'yes') {
                console.log('📦 Adding MySQL service...');
                try {
                    execSync('railway add mysql', { stdio: 'inherit' });
                    console.log('✅ MySQL service added');
                } catch (error) {
                    console.log('⚠️  MySQL service may already exist or failed to add');
                }

                // MySQL configuration using Railway variables
                variables = {
                    'APP_NAME': 'Laravel SSO',
                    'APP_ENV': 'production',
                    'APP_DEBUG': 'false',
                    'APP_KEY': appKey,
                    'DB_CONNECTION': 'mysql',
                    'DB_HOST': '${{RAILWAY_PRIVATE_DOMAIN}}',
                    'DB_PORT': '3306',
                    'DB_DATABASE': 'railway',
                    'DB_USERNAME': 'root',
                    'DB_PASSWORD': '${{MYSQL_ROOT_PASSWORD}}',
                    'SESSION_DRIVER': 'file',
                    'SESSION_LIFETIME': '120',
                    'SESSION_ENCRYPT': 'false',
                    'SESSION_PATH': '/',
                    'SESSION_DOMAIN': '',
                    'CACHE_DRIVER': 'file'
                };
            } else {
                // SQLite configuration (default)
                variables = {
                    'APP_NAME': 'Laravel SSO',
                    'APP_ENV': 'production',
                    'APP_DEBUG': 'false',
                    'APP_KEY': appKey,
                    'DB_CONNECTION': 'sqlite',
                    'DB_DATABASE': '/app/storage/database/database.sqlite',
                    'SESSION_DRIVER': 'file',
                    'SESSION_LIFETIME': '120',
                    'SESSION_ENCRYPT': 'false',
                    'SESSION_PATH': '/',
                    'SESSION_DOMAIN': '',
                    'CACHE_DRIVER': 'file'
                };
            }

            try {
                for (const [key, value] of Object.entries(variables)) {
                    execSync(`railway variables set ${key}="${value}"`, { stdio: 'pipe' });
                    console.log(`✅ Set ${key}`);
                }

                console.log('\n🔴 IMPORTANT: You still need to set Google OAuth variables:');
                console.log('   railway variables set GOOGLE_CLIENT_ID="your-client-id"');
                console.log('   railway variables set GOOGLE_CLIENT_SECRET="your-client-secret"');
                console.log('   railway variables set GOOGLE_REDIRECT_URI="https://your-domain.railway.app/auth/google/callback"');

                resolve(true);
            } catch (error) {
                console.error('❌ Failed to set variables:', error.message);
                resolve(false);
            }
        });
    });
}// Deploy application
function deployApplication() {
    try {
        console.log('\n🚀 Deploying to Railway...');
        execSync('railway up', { stdio: 'inherit' });

        console.log('\n✅ Deployment complete!');

        // Try to get the URL
        try {
            const status = execSync('railway status --json', { encoding: 'utf8' });
            const statusData = JSON.parse(status);
            if (statusData.deployments && statusData.deployments[0] && statusData.deployments[0].url) {
                const url = statusData.deployments[0].url;
                console.log(`📱 Your app is available at: ${url}`);

                // Set APP_URL
                execSync(`railway variables set APP_URL="${url}"`, { stdio: 'pipe' });
                console.log('✅ APP_URL updated');
            }
        } catch (urlError) {
            console.log('📱 Check your Railway dashboard for your app URL');
        }

        return true;
    } catch (error) {
        console.error('❌ Deployment failed:', error.message);
        return false;
    }
}

// Main setup function
async function main() {
    try {
        if (!checkRailwayCLI()) {
            process.exit(1);
        }

        if (!await setupRailway()) {
            process.exit(1);
        }

        if (!await setEnvironmentVariables()) {
            process.exit(1);
        }        if (!deployApplication()) {
            process.exit(1);
        }

        console.log('\n🎉 Railway setup complete!');
        console.log('\n📋 Next steps:');
        console.log('1. Set up Google OAuth in Google Cloud Console');
        console.log('2. Add your Railway domain to Google OAuth redirect URIs');
        console.log('3. Set the GOOGLE_* environment variables');
        console.log('4. Visit your app and test the SSO functionality');

    } catch (error) {
        console.error('❌ Setup failed:', error.message);
        process.exit(1);
    }
}

// Run the setup
main();
