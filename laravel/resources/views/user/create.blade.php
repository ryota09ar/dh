<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>新規登録</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{route('user.store')}}" method="post">
        @csrf
        <label for="name">名前</label>
        <input id="name" name="name" type="text">
        <label for="email">メールアドレス</label>
        <input id="email" name="email" type="email">
        <label for="password">パスワード</label>
        <input id="password" name="password" type="password">
        <button>登録</button>
    </form>
    <a href="{{route("login")}}">ログインへ</a>
</body>
</html>
