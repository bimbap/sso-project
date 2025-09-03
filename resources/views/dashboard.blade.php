@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Dashboard</h4>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- User Info -->
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body text-center">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle mb-3" width="80" height="80">
                                    @endif
                                    <h5>{{ $user->name }}</h5>
                                    <p class="text-muted">{{ $user->email }}</p>
                                    <p class="small">Last Activity: {{ $user->last_activity->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">Quick Actions</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <a href="{{ route('posts.create') }}" class="btn btn-primary w-100">
                                                <i class="fas fa-plus"></i> Create Post
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="{{ route('posts.index') }}" class="btn btn-secondary w-100">
                                                <i class="fas fa-list"></i> View All Posts
                                            </a>
                                        </div>
                                        @if($user->role === 'admin')
                                        <div class="col-md-6 mb-3">
                                            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger w-100">
                                                <i class="fas fa-cog"></i> Admin Panel
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <a href="{{ route('admin.users') }}" class="btn btn-warning w-100">
                                                <i class="fas fa-users"></i> Manage Users
                                            </a>
                                        </div>
                                        @endif
                                        @if($user->role === 'member')
                                        <div class="col-md-6 mb-3">
                                            <a href="{{ route('member.dashboard') }}" class="btn btn-info w-100">
                                                <i class="fas fa-user"></i> Member Area
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Recent Posts</div>
                                <div class="card-body">
                                    @if($recentPosts->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($recentPosts as $post)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ Str::limit($post->title, 30) }}</h6>
                                                    <small>by {{ $post->user->name }}</small>
                                                </div>
                                                <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">
                                                    {{ $post->status }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">No posts available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">My Posts</div>
                                <div class="card-body">
                                    @if($userPosts->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($userPosts as $post)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ Str::limit($post->title, 30) }}</h6>
                                                    <small>{{ $post->created_at->diffForHumans() }}</small>
                                                </div>
                                                <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">
                                                    {{ $post->status }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">You haven't created any posts yet.</p>
                                        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Create Your First Post</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
