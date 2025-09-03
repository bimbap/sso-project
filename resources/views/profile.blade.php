@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-user"></i> User Profile</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Profile Picture -->
                        <div class="col-md-4 text-center">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="max-width: 200px;">
                            @else
                                <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                                    <i class="fas fa-user fa-5x text-white"></i>
                                </div>
                            @endif

                            <h5>{{ $user->name }}</h5>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} mb-3">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        <!-- Profile Information -->
                        <div class="col-md-8">
                            <h6>Profile Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role:</th>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Joined:</th>
                                    <td>{{ $user->created_at->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Activity:</th>
                                    <td>
                                        @if($user->last_activity)
                                            {{ $user->last_activity->diffForHumans() }}
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Posts Count:</th>
                                    <td>{{ $user->posts()->count() }} posts</td>
                                </tr>
                            </table>

                            <div class="mt-4">
                                <h6>Account Statistics</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-white text-center">
                                            <div class="card-body">
                                                <h5>{{ $user->posts()->count() }}</h5>
                                                <small>Total Posts</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-success text-white text-center">
                                            <div class="card-body">
                                                <h5>{{ $user->posts()->where('status', 'published')->count() }}</h5>
                                                <small>Published Posts</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-secondary text-white text-center">
                                            <div class="card-body">
                                                <h5>{{ $user->posts()->where('status', 'draft')->count() }}</h5>
                                                <small>Draft Posts</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="mt-4">
                        <h6>Recent Posts</h6>
                        @php $recentPosts = $user->posts()->latest()->take(5)->get(); @endphp

                        @if($recentPosts->count() > 0)
                            <div class="list-group">
                                @foreach($recentPosts as $post)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                                    {{ $post->title }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">View All Posts</a>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">You haven't created any posts yet.</p>
                                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Your First Post</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
