@extends('layouts.index')

@section('content')

<h1>my blog list</h1>

<div>
    <a href="/mypage/blogs/create">Post a new article</a>
</div>

<table>
    <tr>
        <th>Blog Name</th>
    </tr>

    @foreach ( $blogs as $blog )
    <tr>
        <td>
            {{ $blog->title }}
        </td>
    </tr>
    @endforeach
</table>

@endsection