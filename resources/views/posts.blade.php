@extends('layouts.app')

@section('style')
<style>
    .post-img { height: 180px; object-fit: cover; }
    i { cursor: pointer; }
    .spinner-border { display: none; width: 1rem; height: 1rem; }
    .comment-section { max-height: 150px; overflow-y: auto; background: #f8f9fa; padding: 10px; border-radius: 8px; margin-top: 10px; }
    .comment-item { border-bottom: 1px solid #dee2e6; padding: 5px 0; }
    .comment-item:last-child { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="container">
    <h4 class="mb-3">🔥 Top Liked Posts</h4>
    <div class="row mb-4" id="top-posts-section">
        @forelse($topPosts as $post)
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h6>{{ $post->title }}</h6>
                    <p>👍 {{ $post->likes_count }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">No top posts yet.</div>
        @endforelse
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Posts List</div>
        <div class="card-body">
            <div class="row">
                @forelse($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $post->image ? asset('images/'.$post->image) : 'https://picsum.photos/300/200?random='.$post->id }}" class="card-img-top post-img">
                        <div class="card-body">
                            <h6 class="fw-bold">{{ $post->title }}</h6>
                            <p class="text-muted small">{{ $post->body }}</p>
                            
                            <div class="reaction-msg mb-2">
                                @if(auth()->user()->hasLiked($post->id))
                                    <small class="text-success">You liked this</small>
                                @elseif(auth()->user()->hasDisliked($post->id))
                                    <small class="text-danger">You disliked this</small>
                                @endif
                            </div>

                            <hr>

                            <div class="comments-container">
                                <h6 class="small fw-bold">Comments ({{ $post->comments->count() }})</h6>
                                <div class="comment-section mb-2">
                                    @forelse($post->comments as $comment)
                                        <div class="comment-item">
                                            <small class="fw-bold text-primary">{{ $comment->user->name }}:</small>
                                            <small class="text-dark">{{ $comment->body }}</small>
                                        </div>
                                    @empty
                                        <small class="text-muted">No comments yet.</small>
                                    @endforelse
                                </div>

                                <form action="{{ route('comments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="body" class="form-control" placeholder="Write a comment..." required>
                                        <button class="btn btn-outline-primary" type="submit">Post</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0">
                            <div class="d-flex align-items-center gap-3">
                                <span>
                                    <i data-id="{{ $post->id }}" class="like fa-thumbs-up {{ auth()->user()->hasLiked($post->id) ? 'fa-solid text-success' : 'fa-regular' }}"></i>
                                    <span class="like-count">{{ $post->likes->count() }}</span>
                                </span>
                                <span>
                                    <i data-id="{{ $post->id }}" class="dislike fa-thumbs-down {{ auth()->user()->hasDisliked($post->id) ? 'fa-solid text-danger' : 'fa-regular' }}"></i>
                                    <span class="dislike-count">{{ $post->dislikes->count() }}</span>
                                </span>
                                <div class="spinner-border text-primary loader-{{ $post->id }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h4>No posts found</h4>
                </div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });

$('.like, .dislike').click(function () {
    let id = $(this).data('id');
    let like = $(this).hasClass('like') ? 1 : 0;
    let btn = $(this);
    let loader = $('.loader-' + id);

    btn.hide();
    loader.show();

    $.post("{{ route('posts.ajax.like.dislike') }}", { id: id, like: like }, function (res) {
        loader.hide();
        btn.show();

        let footer = btn.closest('.card-footer');
        let body = btn.closest('.card').find('.card-body');

        footer.find('.like-count').text(res.likes);
        footer.find('.dislike-count').text(res.dislikes);

        footer.find('.like').removeClass('fa-solid text-success').addClass('fa-regular');
        footer.find('.dislike').removeClass('fa-solid text-danger').addClass('fa-regular');

        if (res.status === 'created' || res.status === 'updated') {
            if (like == 1) {
                btn.removeClass('fa-regular').addClass('fa-solid text-success');
                body.find('.reaction-msg').html('<small class="text-success">You liked this</small>');
            } else {
                btn.removeClass('fa-regular').addClass('fa-solid text-danger');
                body.find('.reaction-msg').html('<small class="text-danger">You disliked this</small>');
            }
        } else {
            body.find('.reaction-msg').html('');
        }

        $.get('/top-posts', function(data) {
            let html = '';
            data.forEach(p => {
                html += `<div class="col-md-4"><div class="card border-success shadow-sm"><div class="card-body"><h6>${p.title}</h6><p>👍 ${p.likes_count}</p></div></div></div>`;
            });
            $('#top-posts-section').html(html);
        });

        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Action recorded', showConfirmButton: false, timer: 1500 });
    });
});
</script>
@endsection