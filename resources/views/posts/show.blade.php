@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ $post->title }}</h4>
                    <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'secondary' : 'warning') }}">
                        {{ ucfirst($post->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">
                            By {{ $post->user->name }} •
                            Created {{ $post->created_at->diffForHumans() }}
                            @if($post->updated_at != $post->created_at)
                                • Updated {{ $post->updated_at->diffForHumans() }}
                            @endif
                        </small>
                    </div>

                    <div class="post-content">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    @if(Auth::user()->id === $post->user_id || Auth::user()->role === 'admin')
                    <div class="mt-4 pt-3 border-top">
                        <div class="btn-group" role="group">
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer">
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Posts
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
