<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel SSO App - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .google-btn {
            background: #4285f4;
            color: white !important;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(66, 133, 244, 0.2);
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <div style="font-size: 4rem; color: #667eea; margin-bottom: 1rem;">
                🔒
            </div>

            <h1 class="h2" style="color: #2c3e50; margin-bottom: 1rem; font-weight: 700;">
                Laravel SSO App
            </h1>

            <p style="color: #6c757d; margin-bottom: 2rem;">
                Secure Single Sign-On with Google Authentication.<br>
                Access your dashboard with role-based permissions.
            </p>

            <a href="/auth/google" class="google-btn">
                🔑 Sign in with Google
            </a>

            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e9ecef;">
                <h5 style="color: #495057; margin-bottom: 1.5rem;">Key Features</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div style="color: #667eea; font-size: 1.5rem; margin-bottom: 0.5rem;">
                            🔐
                        </div>
                        <span>Google OAuth 2.0</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div style="color: #667eea; font-size: 1.5rem; margin-bottom: 0.5rem;">
                            👥
                        </div>
                        <span>Role-Based Access</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div style="color: #667eea; font-size: 1.5rem; margin-bottom: 0.5rem;">
                            🔄
                        </div>
                        <span>Desktop API Integration</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div style="color: #667eea; font-size: 1.5rem; margin-bottom: 0.5rem;">
                            🛡️
                        </div>
                        <span>Session Security</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
