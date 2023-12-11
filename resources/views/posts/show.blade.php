@extends('layouts.app')

@section('title', 'Post body')

@section('content')
<ul>
    <li>{{ $post->post_body }}</li>
    <li>Posted by: {{ $post->user->name }}</li>
    <li>
        Tags:
        @foreach ($post->tags as $tag)
        <a href="{{ route('posts.indexByTag', $tag->id) }}">{{ $tag->tag_body }}</a>
        @if (!$loop->last)
        ,
        @endif
        @endforeach
    </li>
</ul>

<div id="comments">
    <h2>Comments</h2>
    <ul id="comment-list">
        @foreach($post->comments as $comment)
        <li>{{ $comment->comment_body }} - {{ $comment->user->name }}</li>
        @endforeach
    </ul>

    @if(auth()->check())
    <form id="comment-form">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <textarea name="comment_body" placeholder="Add a comment"></textarea>
        <button type="submit">Submit</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const commentForm = document.getElementById('comment-form');
            const commentList = document.getElementById('comment-list');

            commentForm.addEventListener('submit', function (e) {

                const formData = new FormData(commentForm);
                fetch('{{ route('comments.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const newComment = document.createElement('li');
                            newComment.textContent = data.comment.comment_body + ' - ' + data.comment.user.name;
                            commentList.appendChild(newComment);

                            commentForm.reset();
                        } else {
                            console.error(data.error);
                        }
                    })
                    .catch(error => console.error(error));
            });
        });
    </script>
    @else
    <p>Please login to leave a comment.</p>
    @endif
</div>

@if(auth()->id() === $post->user_id)
<form method="POST" action="{{ route('posts.destroy', $post->id) }}">
    @csrf
    @method('DELETE')
    <button type="submit">Delete Post</button>
</form>
@else
<p>You do not have permission to delete this post.</p>
@endif
@endsection