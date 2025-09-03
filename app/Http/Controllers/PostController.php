<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published,archived',
        ]);

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Check if user owns the post or is admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->id !== $post->user_id && !$user->isAdmin()) {
            abort(403, 'Unauthorized to edit this post.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Check if user owns the post or is admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->id !== $post->user_id && !$user->isAdmin()) {
            abort(403, 'Unauthorized to update this post.');
        }

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published,archived',
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Check if user owns the post or is admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->id !== $post->user_id && !$user->isAdmin()) {
            abort(403, 'Unauthorized to delete this post.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
