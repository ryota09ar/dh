<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/user/createComplete.css") }}?v={{ time() }}">
    <title>complete</title>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="{{ route("user.home") }}">メニュー</a></li>
            </ol>
        </div>
    </header>
    <main>
        <h1>登録完了♪</h1>
        <a href="{{route("login")}}">ログインへ</a>
    </main>

</body>
</html>
