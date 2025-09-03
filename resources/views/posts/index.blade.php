@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Posts</h4>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
                </div>

                <div class="card-body">
                    @if($posts->count() > 0)
                        <div class="row">
                            @foreach($posts as $post)
                            <div class="col-md-12 mb-3">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0">{{ $post->title }}</h5>
                                            <small class="text-muted">by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'secondary' : 'warning') }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{{ Str::limit($post->content, 200) }}</p>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary btn-sm">View</a>
                                            @if(Auth::user()->id === $post->user_id || Auth::user()->role === 'admin')
                                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                            <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h5>No posts available</h5>
                            <p class="text-muted">Be the first to create a post!</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Your First Post</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
