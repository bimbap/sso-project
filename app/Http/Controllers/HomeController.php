<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Main dashboard
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $recentPosts = Post::with('user')->latest()->take(5)->get();
        $userPosts = $user->posts()->latest()->take(3)->get();

        return view('dashboard', compact('user', 'recentPosts', 'userPosts'));
    }

    /**
     * User profile
     */
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Member dashboard
     */
    public function memberDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $memberPosts = Post::where('user_id', $user->id)->latest()->paginate(10);

        return view('member.dashboard', compact('user', 'memberPosts'));
    }
}
