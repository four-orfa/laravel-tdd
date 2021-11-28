@extends('layouts.index')

@section('content')

<h1>Login menu</h1>

<form method="post" action="login">
    @csrf

    @include('inc.error')

    @include('inc.status')

    <div>
        <p>email : </p>
        <input type="email" name="email" value="{{ old('email') }}">
    </div>
    <div>
        <p>password : </p>
        <input type="password" name="password">
    </div>
    <div>
        <input type="submit" value="submit">
    </div>

    <div>
        <p>
            <a href="/signup">sign up</a>
        </p>
    </div>
</form>

@endsection