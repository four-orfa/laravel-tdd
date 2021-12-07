@extends('layouts.index')

@section('content')

<h1>MyBlog Create</h1>

<form method="post">
    @csrf
    @include('inc.error')

    <div>Title : <input type="text" name="title" style="width:400px" value="{{ old('title') }}"></div>
    <div>Body : <textarea name="body" cols="30" rows="10">{{ old('body') }}</textarea></div>
    <div> Publish : <input type="checkbox" name="status" value="1" {{ old('status') ? 'checked' : '' }}>Release</div>

    <div><input type="submit" value="submit"></div>
</form>


@endsection