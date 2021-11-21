@extends('layouts.index')

@section('content')

@if (today()->is('1-1'))
<h1>Happy NewYear!</h1>
@endif

<h1>{{ $blog->title }}</h1>
<div>{{ nl2br(e($blog->body)) }}</div>

<p>name : {{ $blog->user->name }}</p>

@endsection