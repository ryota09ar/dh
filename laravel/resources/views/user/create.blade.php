<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/user/create.css") }}?v={{ time() }}">
    <title>signUpFor</title>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="{{ route("login") }}">ログイン</a></li>
                <li><a href="#">新規登録</a></li>
            </ol>
        </div>
    </header>
    <main>
        <div class="container">
            <form action="{{route('user.store')}}" method="post">
                @csrf
                <label for="name">名前</label>
                @if($errors->has("family_name") xor $errors->has("first_name"))
                    <p class="alert alert-danger">{{ $errors->first("family_name") }}{{ $errors->first("first_name") }}</p>
                @elseif($errors->has("family_name") && $errors->has("first_name"))
                    <p class="alert alert-danger">{{ $errors->first("family_name") }}</p>
                @endif
                姓<input id="name" name="family_name" type="text">
                名<input id="name" name="first_name" type="text">
                <label for="email">メールアドレス</label>
                @if($errors->has("email"))
                    <p class="alert alert-danger">{{ $errors->first("email") }}</p>
                @endif
                <input id="email" name="email" type="email">
                <label for="password">パスワード</label>
                @if($errors->has("password"))
                    <p class="alert alert-danger">{{ $errors->first("password") }}</p>
                @endif
                <input id="password" name="password" type="password">
                <button>登録</button>
            </form>
        </div>
    </main>

</body>
</html>
