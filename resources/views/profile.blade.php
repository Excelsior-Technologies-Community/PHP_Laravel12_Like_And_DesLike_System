@extends('layouts.app')

@section('style')
<style>
    .profile-card { border-radius: 15px; overflow: hidden; }
    .post-img { height: 150px; object-fit: cover; border-radius: 10px; }
    .liked-badge { background: #e8f5e9; color: #2e7d32; font-weight: bold; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card profile-card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fa-solid fa-circle-user fa-5x text-secondary"></i>
                    </div>
                    <h4 class="fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <hr>
                    <div class="d-flex justify-content-around">
                        <div>
                            <h6 class="mb-0">Joined</h6>
                            <small>{{ $user->created_at->format('M Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card profile-card shadow-sm">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fa-solid fa-heart text-danger me-2"></i> Posts You Liked
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($likedPosts as $post)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-light shadow-none" style="background: #fdfdfd;">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <img src="{{ $post->image ? asset('images/'.$post->image) : 'https://picsum.photos/200?random='.$post->id }}" class="img-fluid post-img w-100">
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-2">
                                            <h6 class="card-title fw-bold mb-1 text-truncate">{{ $post->title }}</h6>
                                            <div class="liked-badge d-inline-block">
                                                <i class="fa-solid fa-thumbs-up"></i> Liked
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="fa-regular fa-face-meh fa-3x text-muted mb-3"></i>
                            <p class="text-muted">You haven't liked any posts yet.</p>
                            <a href="{{ route('posts.index') }}" class="btn btn-primary btn-sm">Browse Posts</a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection