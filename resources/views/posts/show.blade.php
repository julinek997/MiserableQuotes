@extends('layouts.app')

@section('title', 'Post body')

@section('content')
    <ul>
        <li>{{ $post->post_body }}</li>
        <li>Posted by: {{ $post->user->name  }} </li>
    </ul>
    @endsection