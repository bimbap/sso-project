<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find existing user by Google ID or email
            $user = User::where('google_id', $googleUser->id)
                       ->orWhere('email', $googleUser->email)
                       ->first();

            if ($user) {
                // Update existing user's Google information
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'last_activity' => now(),
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(16)), // Random password
                    'role' => 'member', // Default role
                    'last_activity' => now(),
                    'api_token' => Str::random(80), // API token for desktop app
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed. Please try again.');
        }
    }

    /**
     * API login for desktop application
     */
    public function apiLogin(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            // Verify the access token with Google
            $response = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $request->access_token);
            $googleUser = json_decode($response, true);

            if (!$googleUser || !isset($googleUser['id'])) {
                throw new \Exception('Invalid access token');
            }

            $user = User::where('google_id', $googleUser['id'])
                       ->orWhere('email', $googleUser['email'])
                       ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser['name'],
                    'email' => $googleUser['email'],
                    'google_id' => $googleUser['id'],
                    'avatar' => $googleUser['picture'] ?? null,
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'member',
                    'last_activity' => now(),
                    'api_token' => Str::random(80),
                ]);
            } else {
                $user->update([
                    'last_activity' => now(),
                ]);
            }

            $token = $user->createToken('desktop-app')->plainTextToken;            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed',
            ], 401);
        }
    }
}
