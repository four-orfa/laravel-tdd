@extends('layouts.index')

@section('content')

<h1>Sign Up</h1>

<form action="post">
    @csrf

    @include('inc.error')

    <div>
        Name : <input type="text" name="name" value="{{ old('name') }}">
    </div>
    <div>
        EMail : <input type="email" name="email" value="{{ old('email') }}">
    </div>
    <div>
        password : <input type="password" name="password">
    </div>
    <input type="submit" value="submit">
</form>
@endsection