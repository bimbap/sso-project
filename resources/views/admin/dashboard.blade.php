@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4><i class="fas fa-cog"></i> Admin Dashboard</h4>
                </div>

                <div class="card-body">
                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['total_users'] }}</h3>
                                    <p>Total Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['total_posts'] }}</h3>
                                    <p>Total Posts</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>{{ App\Models\User::where('role', 'admin')->count() }}</h3>
                                    <p>Admins</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ App\Models\User::where('role', 'member')->count() }}</h3>
                                    <p>Members</p>
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
                                        <a href="{{ route('admin.users') }}" class="btn btn-primary">
                                            <i class="fas fa-users"></i> Manage Users
                                        </a>
                                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-list"></i> View All Posts
                                        </a>
                                        <a href="{{ route('posts.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus"></i> Create Post
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Users and Posts -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Recent Users</div>
                                <div class="card-body">
                                    @if($stats['recent_users']->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($stats['recent_users'] as $user)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    @if($user->avatar)
                                                        <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">No users found.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Recent Posts</div>
                                <div class="card-body">
                                    @if($stats['recent_posts']->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($stats['recent_posts'] as $post)
                                            <div class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">{{ Str::limit($post->title, 30) }}</h6>
                                                        <small>by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">
                                                        {{ $post->status }}
                                                    </span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">No posts found.</p>
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
