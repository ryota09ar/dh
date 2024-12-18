<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/login/view.css") }}?v={{ time() }}">
    <title>manavisdh</title>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="#">ログイン</a></li>
            </ol>
        </div>
    </header>
    <main>
        <div class="container">
        @if($errors->has("invalid"))
            <p class="alert alert-danger">{{ $errors->first("invalid") }}</p>
        @endif
            <form action="{{route("login.home")}}" method="post">
                @csrf
                <label for="email">メールアドレス</label>
                @if($errors->has("email"))
                    <p class="alert alert-danger">{{ $errors->first("email") }}</p>
                @endif
                <input id="email" name="email" type="email" required>
                <label for="password">パスワード</label>
                @if($errors->has("password"))
                    <p class="alert alert-danger">{{ $errors->first("password") }}</p>
                @endif
                <input id="password" name="password" type="password" required>
                <button>ログイン</button>
            </form>
            <a href="{{route("user.create")}}">新規登録</a>
            <a href="{{route("user.edit")}}">パスワードを忘れた</a>
        </div>
    </main>


</body>
</html>
