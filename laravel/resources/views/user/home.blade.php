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
    <h1>メニュー</h1>
    <h2>{{ \Illuminate\Support\Facades\Auth::user()->family_name.\Illuminate\Support\Facades\Auth::user()->first_name }} さん</h2>
    <a href="{{ route("shiftRequest.create", ['year' => (((int)date("n")==12) ? (int)date("Y")+1:(int)date("Y")), 'month' => (int)date("n")+1]) }}">シフト提出</a>
    <a href="">シフト確認</a>
    <a href="{{route("logout")}}">ログアウト</a>
</body>
</html>
