<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Test route to verify server is working
Route::get('/test', function () {
    return 'Laravel server is working!';
});

// Health check route for Railway
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'app' => config('app.name'),
        'environment' => config('app.env')
    ]);
});

// Test view rendering
Route::get('/test-view', function () {
    return view('welcome');
});

// Simple test view
Route::get('/test-simple', function () {
    return view('test');
});

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Authentication routes (keeping traditional auth as backup)
Auth::routes(['register' => false]); // Disable registration since we use Google OAuth

// Protected routes
Route::middleware(['auth', 'secure.session'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');

    // Posts CRUD - accessible by all authenticated users
    Route::resource('posts', PostController::class);

    // Member-only routes
    Route::middleware('role:member')->group(function () {
        Route::get('/member/dashboard', [HomeController::class, 'memberDashboard'])->name('member.dashboard');
    });

    // Admin-only routes (temporarily disabled for debugging)
    // Route::middleware('role:admin')->group(function () {
    //     Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    //     Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    //     Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
    // });
});

// Legacy home route redirect
Route::get('/home', function () {
    return redirect('/dashboard');
})->name('home');
