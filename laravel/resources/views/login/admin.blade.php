<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/login/view.css") }}?v={{ time() }}">
    <title>login</title>
</head>
<body>
<header>
    <div class="container">
        <ol class="breadcrumb-004">
            <li><a href="#">管理者ログイン</a></li>
        </ol>
    </div>
</header>
<main>
    <div class="container">
        @if($errors->has("invalid"))
            <p class="alert alert-danger">{{ $errors->first("invalid") }}</p>
        @endif
        <form action="{{route("admin.login.home")}}" method="post">
            @csrf
            <label for="admin_id">ID</label>
            @if($errors->has("email"))
                <p class="alert alert-danger">{{ $errors->first("email") }}</p>
            @endif
            <input id="admin_id" name="email" type="email" required>
            <label for="password">パスワード</label>
            @if($errors->has("password"))
                <p class="alert alert-danger">{{ $errors->first("password") }}</p>
            @endif
            <input id="password" name="password" type="password" required>
            <button>ログイン</button>
        </form>
    </div>
</main>


</body>
</html>
