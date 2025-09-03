@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4><i class="fas fa-user-circle"></i> Member Dashboard</h4>
                </div>

                <div class="card-body">
                    <!-- Welcome Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-hand-wave"></i> Welcome back, {{ $user->name }}!</h5>
                                <p class="mb-0">This is your member dashboard where you can manage your posts and view your activity.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $memberPosts->total() }}</h3>
                                    <p>My Posts</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $user->posts()->where('status', 'published')->count() }}</h3>
                                    <p>Published</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $user->posts()->where('status', 'draft')->count() }}</h3>
                                    <p>Drafts</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $user->posts()->where('status', 'archived')->count() }}</h3>
                                    <p>Archived</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Quick Actions</div>
                                <div class="card-body">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('posts.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus"></i> Create New Post
                                        </a>
                                        <a href="{{ route('posts.index') }}" class="btn btn-primary">
                                            <i class="fas fa-list"></i> View All Posts
                                        </a>
                                        <a href="{{ route('profile') }}" class="btn btn-info">
                                            <i class="fas fa-user"></i> View Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Posts -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5><i class="fas fa-file-alt"></i> My Posts</h5>
                                    <small class="text-muted">{{ $memberPosts->total() }} total posts</small>
                                </div>
                                <div class="card-body">
                                    @if($memberPosts->count() > 0)
                                        <div class="row">
                                            @foreach($memberPosts as $post)
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0">{{ Str::limit($post->title, 30) }}</h6>
                                                        <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'secondary' : 'warning') }}">
                                                            {{ ucfirst($post->status) }}
                                                        </span>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                                                        <small class="text-muted">
                                                            Created {{ $post->created_at->diffForHumans() }}
                                                            @if($post->updated_at != $post->created_at)
                                                                • Updated {{ $post->updated_at->diffForHumans() }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-secondary">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        <!-- Pagination -->
                                        <div class="d-flex justify-content-center">
                                            {{ $memberPosts->links() }}
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-file-plus fa-3x text-muted mb-3"></i>
                                            <h5>No posts yet</h5>
                                            <p class="text-muted">You haven't created any posts yet. Start by creating your first post!</p>
                                            <a href="{{ route('posts.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Create Your First Post
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Main Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
