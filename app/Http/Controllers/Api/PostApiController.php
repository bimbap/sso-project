<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostApiController extends Controller
{
    /**
     * Display a listing of posts
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return response()->json($posts);
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published,archived',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        $post->load('user');

        return response()->json([
            'success' => true,
            'post' => $post,
            'message' => 'Post created successfully'
        ], 201);
    }

    /**
     * Display the specified post
     */
    public function show(Post $post)
    {
        $post->load('user');
        return response()->json($post);
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        // Check authorization
        if (Auth::user()->id !== $post->user_id && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
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

        $post->load('user');

        return response()->json([
            'success' => true,
            'post' => $post,
            'message' => 'Post updated successfully'
        ]);
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        // Check authorization
        if (Auth::user()->id !== $post->user_id && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    /**
     * Sync posts for desktop app
     */
    public function sync()
    {
        $posts = Post::with('user')->latest()->get();
        return response()->json([
            'posts' => $posts,
            'timestamp' => now()->timestamp,
        ]);
    }

    /**
     * Get changes since timestamp
     */
    public function getChanges($timestamp)
    {
        $date = \Carbon\Carbon::createFromTimestamp($timestamp);

        $posts = Post::with('user')
                    ->where('updated_at', '>', $date)
                    ->latest()
                    ->get();

        return response()->json([
            'posts' => $posts,
            'timestamp' => now()->timestamp,
        ]);
    }
}
