<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Blog</title>
</head>

<body>

    <nav>
        <li><a href="/">TOP (blogs list)</a></li>
    </nav>

    @auth
    <li><a href="/mypage/blogs/">My Blogs</a></li>
    <li>
        <form method="post" action="/mypage/logout">
            @csrf
            <input type="submit" value="Logout">
        </form>
    </li>
    @else
    <li><a href="{{ route('login') }}">Login</a></li>
    @endauth

    @yield('content')

</body>

</html>