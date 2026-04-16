@extends('layouts.app')

@section('style')
<style>
    .post-img {
        height: 180px;
        object-fit: cover;
    }
    i {
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container">

    <!-- Top Liked Posts -->
    <h4 class="mb-3">🔥 Top Liked Posts</h4>

    <!-- IMPORTANT: added ID -->
    <div class="row mb-4" id="top-posts-section">
        @foreach($topPosts as $post)
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h6>{{ $post->title }}</h6>
                    <p>👍 {{ $post->likes_count }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- All Posts -->
    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            Posts List
        </div>

        <div class="card-body">
            <div class="row">

                @foreach($posts as $post)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">

                        <!-- Image -->
                        <img src="https://picsum.photos/300/200?random={{ $post->id }}"
                             class="card-img-top post-img">

                        <div class="card-body">
                            <h6 class="fw-bold">{{ $post->title }}</h6>
                            <p class="text-muted small">{{ $post->body }}</p>

                            <!-- Reaction Message -->
                            <div class="reaction-msg">
                                @if(auth()->user()->hasLiked($post->id))
                                    <small class="text-success">You liked this</small>
                                @elseif(auth()->user()->hasDisliked($post->id))
                                    <small class="text-danger">You disliked this</small>
                                @endif
                            </div>
                        </div>

                        <!-- Like Dislike -->
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex align-items-center gap-3">

                                <span>
                                    <i data-id="{{ $post->id }}"
                                       class="like fa-thumbs-up
                                       {{ auth()->user()->hasLiked($post->id) ? 'fa-solid text-success' : 'fa-regular' }}">
                                    </i>
                                    <span class="like-count">{{ $post->likes->count() }}</span>
                                </span>

                                <span>
                                    <i data-id="{{ $post->id }}"
                                       class="dislike fa-thumbs-down
                                       {{ auth()->user()->hasDisliked($post->id) ? 'fa-solid text-danger' : 'fa-regular' }}">
                                    </i>
                                    <span class="dislike-count">{{ $post->dislikes->count() }}</span>
                                </span>

                            </div>
                        </div>

                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

$('.like, .dislike').click(function () {

    let id = $(this).data('id');
    let like = $(this).hasClass('like') ? 1 : 0;

    let btn = $(this);

    $.post("{{ route('posts.ajax.like.dislike') }}", {
        id: id,
        like: like
    }, function (res) {

        let footer = btn.closest('.card-footer');
        let body = btn.closest('.card').find('.card-body');

        //  Update counts
        footer.find('.like-count').text(res.likes);
        footer.find('.dislike-count').text(res.dislikes);

        //  Reset icons
        footer.find('.like').removeClass('fa-solid text-success').addClass('fa-regular');
        footer.find('.dislike').removeClass('fa-solid text-danger').addClass('fa-regular');

        //  Set active icon + message
        if (like == 1) {
            btn.removeClass('fa-regular').addClass('fa-solid text-success');
            body.find('.reaction-msg').html('<small class="text-success">You liked this</small>');
        } else {
            btn.removeClass('fa-regular').addClass('fa-solid text-danger');
            body.find('.reaction-msg').html('<small class="text-danger">You disliked this</small>');
        }

        //  NEW: Reload Top Posts LIVE
        $.get('/top-posts', function(data) {

            let html = '';

            data.forEach(post => {
                html += `
                    <div class="col-md-4">
                        <div class="card border-success shadow-sm">
                            <div class="card-body">
                                <h6>${post.title}</h6>
                                <p>👍 ${post.likes_count}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#top-posts-section').html(html);
        });

    });
});
</script>
@endsection