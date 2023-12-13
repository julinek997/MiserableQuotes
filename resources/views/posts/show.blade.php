@extends('layouts.app')

@section('title', 'Post body')

@section('content')
<ul>
    <li>{{ $post->post_body }}</li>
    <li>Posted by: <a href="{{ route('profile.show', ['id' => $post->user->id]) }}">
            {{ $post->user->name }}</a></li>
    <li>
        Tags:
        @foreach ($post->tags as $tag)
        <a href="{{ route('posts.indexByTag', $tag->id) }}">{{ $tag->tag_body }}</a>
        @if (!$loop->last)
        ,
        @endif
        @endforeach
    </li>
    <li>
        @if ($post->image)
        <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image">
        @endif
    </li>
</ul>

<div id="comments">
    <h2>Comments</h2>
    <ul id="comment-list">
        @foreach($post->comments as $comment)
        <li>
            {{ $comment->comment_body }} - <a href="{{ route('profile.show', 
                ['id' => $comment->user->id]) }}">{{
                $comment->user->name }}</a>
            @if(auth()->id() === $comment->user_id)
            <button class="delete-comment" data-comment-id="{{ $comment->id }}">Delete</button>
            @endif
        </li>
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
            const userId = {{ auth() -> id()
        }};

        commentForm.addEventListener('submit', function (e) {
            e.preventDefault();

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

                        if (data.comment && typeof data.comment === 'object') {
                            if (data.comment.user && typeof data.comment.user === 'object') {
                                const commentLink = document.createElement('a');
                                commentLink.href = `/profile/${data.comment.user.id}`;
                                commentLink.textContent = data.comment.user.name;

                                newComment.appendChild(document.createTextNode
                                    (`${data.comment.comment_body} - `));
                                newComment.appendChild(commentLink);

                                if (userId === data.comment.user.id) {
                                    const deleteButton = document.createElement('button');
                                    deleteButton.className = 'delete-comment';
                                    deleteButton.dataset.commentId = data.comment.id;
                                    deleteButton.textContent = 'Delete';

                                    newComment.appendChild(document.createTextNode(' '));
                                    newComment.appendChild(deleteButton);
                                }
                            } else {
                                console.error('User data is missing or invalid:',
                                    data.comment);
                                return;
                            }
                        } else {
                            console.error('Comment data is missing or invalid:',
                                data.comment);
                            return;
                        }

                        commentList.appendChild(newComment);

                        commentForm.reset();
                    } else {
                        console.error(data.error);
                    }
                })
        });

        commentList.addEventListener('click', function (e) {
            if (e.target.tagName === 'BUTTON' &&
                e.target.classList.contains('delete-comment')) {
                const commentId = e.target.dataset.commentId;

                fetch(`{{ route('comments.destroy', '') }}/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            e.target.closest('li').remove();
                        } else {
                            console.error(data.error);
                        }
                    })
                    .catch(error => console.error(error));
            }
        });
        });

    </script>
    @else
    <p>Please login to leave a comment.</p>
    @endif
</div>

@if(auth()->id() === $post->user_id)
<a href="{{ route('posts.edit', $post->id) }}">Edit Post</a>

<form method="POST" action="{{ route('posts.destroy', $post->id) }}">
    @csrf
    @method('DELETE')
    <button type="submit">Delete Post</button>
</form>
@else
<p>You do not have permission to delete this post.</p>
@endif
<li>
    <a href="{{ route('posts.index') }}">Back to Posts</a>
</li>
@endsection