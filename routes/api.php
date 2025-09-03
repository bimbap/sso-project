<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes for desktop app
Route::post('/auth/google', [GoogleController::class, 'apiLogin']);

// Protected API routes for desktop app
Route::middleware('auth:sanctum')->group(function () {
    // User info and profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/user/profile', [UserApiController::class, 'profile']);
    Route::put('/user/profile', [UserApiController::class, 'updateProfile']);

    // Posts CRUD API
    Route::apiResource('posts', PostApiController::class)->names('api.posts');

    // Sync endpoints for real-time updates
    Route::get('/sync/posts', [PostApiController::class, 'sync']);
    Route::get('/sync/posts/changes/{timestamp}', [PostApiController::class, 'getChanges']);

    // Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    });
});

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});
