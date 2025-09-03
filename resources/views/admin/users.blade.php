@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-users"></i> Manage Users</h4>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Avatar</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Last Activity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->last_activity)
                                                {{ $user->last_activity->diffForHumans() }}
                                            @else
                                                <span class="text-muted">Never</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->id !== auth()->id())
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.role', $user) }}" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'member' : 'admin' }}">
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to change this user\'s role?')">
                                                                <i class="fas fa-exchange-alt"></i>
                                                                Make {{ $user->role === 'admin' ? 'Member' : 'Admin' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-info" href="#" onclick="showUserPosts({{ $user->id }}, '{{ $user->name }}')">
                                                            <i class="fas fa-list"></i> View Posts
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            @else
                                            <span class="text-muted">Current User</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h5>No users found</h5>
                            <p class="text-muted">There are no users in the system yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Posts Modal -->
<div class="modal fade" id="userPostsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Posts by <span id="modalUserName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalPostsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showUserPosts(userId, userName) {
    document.getElementById('modalUserName').textContent = userName;
    const modal = new bootstrap.Modal(document.getElementById('userPostsModal'));
    modal.show();

    // Load user posts via AJAX (you would implement this endpoint)
    fetch(`/admin/users/${userId}/posts`)
        .then(response => response.json())
        .then(posts => {
            let content = '';
            if (posts.length > 0) {
                content = '<div class="list-group">';
                posts.forEach(post => {
                    content += `
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${post.title}</h6>
                                    <small>Created ${post.created_at}</small>
                                </div>
                                <span class="badge bg-${post.status === 'published' ? 'success' : 'secondary'}">${post.status}</span>
                            </div>
                        </div>
                    `;
                });
                content += '</div>';
            } else {
                content = '<p class="text-muted text-center">No posts found for this user.</p>';
            }
            document.getElementById('modalPostsContent').innerHTML = content;
        })
        .catch(error => {
            document.getElementById('modalPostsContent').innerHTML = '<p class="text-danger">Error loading posts.</p>';
        });
}
</script>
@endsection
