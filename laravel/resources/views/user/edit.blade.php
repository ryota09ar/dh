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
    <h1>パスワード変更</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{route("user.update")}}" method="post">
        @csrf
        <label for="email">メールアドレス</label>
        <input id="email" name="email" type="email">
        <label for="password">新しいパスワード</label>
        <input id="password" name="password" type="password">
        <button>変更する</button>
    </form>
</body>
</html>
