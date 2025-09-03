# Quick Setup Instructions

## 1. Database Setup

First, create the MySQL database:

```bash
mysql -u root -p
CREATE DATABASE project_sso;
EXIT;
```

## 2. Environment Configuration

Make sure your `.env` file has the correct database configuration:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_sso
DB_USERNAME=root
DB_PASSWORD=
```

## 3. Run Migrations and Seeders

```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
```

## 4. Start the Application

```bash
php artisan serve
```

## 5. Test Login

Visit `http://localhost:8000` and click "Login with Google" or use the test credentials:

- **Admin**: admin@example.com / password
- **Member**: member@example.com / password

## 6. Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create OAuth 2.0 credentials
3. Add these redirect URIs:
   - `http://localhost:8000/auth/google/callback`
   - `http://localhost:8000/api/auth/google`
4. Update your `.env` file:
   ```
   GOOGLE_CLIENT_ID=your-google-client-id
   GOOGLE_CLIENT_SECRET=your-google-client-secret
   ```

## API Endpoints for Desktop App

### Authentication
- `POST /api/auth/google` - Login with Google access token

### User Management
- `GET /api/user` - Get current user
- `GET /api/user/profile` - Get user profile
- `PUT /api/user/profile` - Update profile

### Posts
- `GET /api/posts` - List all posts
- `POST /api/posts` - Create post
- `GET /api/posts/{id}` - Get specific post
- `PUT /api/posts/{id}` - Update post
- `DELETE /api/posts/{id}` - Delete post

### Sync
- `GET /api/sync/posts` - Full sync
- `GET /api/sync/posts/changes/{timestamp}` - Incremental sync

## Desktop App

Run the Python desktop client:

```bash
cd desktop-app-example
pip install -r requirements.txt
python desktop_client.py
```

## Features Included

✅ Google OAuth SSO  
✅ Role-based access (Admin/Member)  
✅ Secure session management  
✅ CRUD operations for posts  
✅ API for desktop integration  
✅ Real-time synchronization  
✅ Responsive Bootstrap UI  
✅ User management (Admin)  

## Troubleshooting

1. **Database errors**: Make sure MySQL is running and database exists
2. **Route errors**: Run `php artisan route:clear && php artisan route:cache`
3. **Asset errors**: Run `npm install && npm run build`
4. **Permission errors**: Make sure storage directories are writable
