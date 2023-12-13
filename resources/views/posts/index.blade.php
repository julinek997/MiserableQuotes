@extends('layouts.app')

@section('content')
    <p>Index of your hopelessness</p>
    <p>Maybe go outside? Current Weather in Swansea: {{ $weather['weather'][0]
        ['description'] }}</p>
    <ul>
        @foreach ($posts as $post)
            <li><a href="/posts/{{ $post->id }}">{{ $post->post_body }}</a></li>
        @endforeach
    </ul>
    {{ $posts->links() }}
    <a href=" {{ route('posts.create') }}">Create Post</a>
@endsection