# Laravel SSO Project with Desktop Integration

This is a comprehensive Laravel application with Google Single Sign-On (SSO) authentication, role-based access control, and desktop application integration.

## Features

### Web Application
- **Google OAuth SSO**: Secure authentication using Google accounts only
- **Role-based Access Control**: Admin and Member roles with different permissions
- **Secure Session Management**: Protected sessions with activity tracking and timeout
- **CRUD System**: Complete Post management system
- **Responsive Bootstrap UI**: Modern, mobile-friendly interface
- **Real-time Activity Tracking**: User session monitoring and security

### API for Desktop Integration
- **RESTful API**: Full API endpoints for desktop app integration
- **Laravel Sanctum**: Secure API authentication with tokens
- **Real-time Sync**: Data synchronization endpoints for seamless integration
- **Desktop OAuth**: Secure authentication flow for desktop applications

### Security Features
- **Middleware Protection**: Custom middleware for role and session security
- **Session Regeneration**: Periodic session ID regeneration for security
- **Activity Timeout**: Automatic logout after 2 hours of inactivity
- **API Token Management**: Secure token-based authentication for desktop apps

## Installation & Setup

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Google OAuth Application (Google Console)

### Step 1: Environment Configuration
1. Copy `.env.example` to `.env`
2. Update database configuration in `.env`
3. Configure Google OAuth credentials:
   ```
   GOOGLE_CLIENT_ID=your-google-client-id
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```

### Step 2: Google OAuth Setup
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add authorized redirect URIs:
   - `http://localhost:8000/auth/google/callback` (Web)
   - `http://localhost:8000/api/auth/google` (Desktop API)

### Step 3: Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE project_sso;
exit

# Run migrations
php artisan migrate

# Optional: Create admin user (run in tinker)
php artisan tinker
$user = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'google_id' => 'dummy_google_id',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

### Step 4: Install Dependencies & Build
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build frontend assets (requires Node.js 20.19+ or 22.12+)
npm run build
```

### Step 5: Start the Application
```bash
# Start Laravel development server
php artisan serve

# Application will be available at: http://localhost:8000
```

## Desktop Application Integration

### Authentication Flow
1. Desktop app redirects user to Google OAuth
2. User authorizes application
3. Desktop app receives access token
4. Desktop app calls `/api/auth/google` with access token
5. API returns user data and Laravel Sanctum token
6. Desktop app uses Sanctum token for all subsequent API calls

### API Endpoints

#### Authentication
- `POST /api/auth/google` - Login with Google access token
- `POST /api/logout` - Logout and invalidate token

#### User Management
- `GET /api/user` - Get authenticated user info
- `GET /api/user/profile` - Get detailed profile
- `PUT /api/user/profile` - Update user profile

#### Posts CRUD
- `GET /api/posts` - List all posts
- `POST /api/posts` - Create new post
- `GET /api/posts/{id}` - Get specific post
- `PUT /api/posts/{id}` - Update post
- `DELETE /api/posts/{id}` - Delete post

#### Synchronization
- `GET /api/sync/posts` - Get all posts with timestamp
- `GET /api/sync/posts/changes/{timestamp}` - Get changes since timestamp

## Role-Based Access Control

### Roles
- **Admin**: Full access to all features, user management, and admin panel
- **Member**: Access to personal posts and member-specific features

### Protected Routes
- Web routes use `role:admin` and `role:member` middleware
- API endpoints check user roles programmatically
- Unauthorized access returns 403 Forbidden

### Permissions
- **All authenticated users**: View all posts, create/edit own posts
- **Members**: Access member dashboard, manage own content
- **Admins**: User management, admin dashboard, edit any post, role assignment

## Security Features

### Session Security
- Session timeout after 2 hours of inactivity
- Periodic session ID regeneration (every 30 minutes)
- Last activity tracking
- Secure session middleware protection

### API Security
- Laravel Sanctum token authentication
- Token-based desktop app access
- Rate limiting on API endpoints
- CORS configuration for cross-origin requests

### OAuth Security
- Google OAuth 2.0 implementation
- Secure redirect URI validation
- State parameter for CSRF protection
- Access token validation

## Troubleshooting

### Common Issues

1. **Google OAuth not working**
   - Check Google Client ID and Secret
   - Verify redirect URI matches exactly
   - Ensure Google+ API is enabled

2. **Database connection issues**
   - Verify MySQL is running
   - Check database credentials in `.env`
   - Ensure database exists

3. **Asset build issues**
   - Update Node.js to version 20.19+ or 22.12+
   - Clear npm cache: `npm cache clean --force`
   - Delete node_modules and reinstall

## License

This project is licensed under the MIT License.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
