@extends('layouts.index')

@section('content')


<h1>My Blog Edit</h1>

<form method="post">
    @csrf
    @include('inc.error')
    @include('inc.status')

    <div>
        <p>title : </p>
        <input type="text" name="title" style="width: 400px;" value="{{ data_get($data, 'title') }}">
    </div>
    <div>
        <p>body : </p>
        <textarea name="body" style="width: 600px; height: 200px">{{ data_get($data, 'body') }}</textarea>
    </div>
    <div>
        <p>public : </p>
        <input type="checkbox" name="status" {{ data_get($data, 'status') ? 'checked' : '' }}>
    </div>

    <input type="submit" value="submit">
</form>

@endsection