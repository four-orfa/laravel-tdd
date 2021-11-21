@extends('layouts.index')

@section('content')

@if (today()->is('1-1'))
<h1>Happy NewYear!</h1>
@endif

<h1>{{ $blog->title }}</h1>
<div>{{ nl2br(e($blog->body)) }}</div>

<p>name : {{ $blog->user->name }}</p>

<h2>comment</h2>
@foreach ($blog->comments()->oldest()->get() as $comment)
<hr>
<p>{{ $comment->name }} ({{ $comment->created_at }})</p>
<p>{{ nl2br(e($comment->body)) }}</p>
@endforeach

@endsection