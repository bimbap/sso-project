<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApiController extends Controller
{
    /**
     * Get user profile
     */
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'posts_count' => $user->posts()->count(),
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Profile updated successfully'
        ]);
    }
}
