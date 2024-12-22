<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/user/home.css") }}?v={{ time() }}">
    <title>home</title>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="#">メニュー</a></li>
            </ol>
            <a href="{{route("logout")}}" class="logout">ログアウト</a>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="menu">
                <h2>{{ \Illuminate\Support\Facades\Auth::user()->family_name.\Illuminate\Support\Facades\Auth::user()->first_name }} さん</h2>
                <a class="button" href="{{ route("shiftRequest.create", ['year' => (((int)date("n")==12) ? (int)date("Y")+1:(int)date("Y")), 'month' => (((int)date("n")==12) ? 1:(int)date("n")+1)]) }}">シフト提出</a>
                <a class="button" href="{{ route("decided.index", ['year' => (((int)date("n")==12) ? (int)date("Y")+1:(int)date("Y")), 'month' => (((int)date("n")==12) ? 1:(int)date("n")+1)]) }}">シフト確認</a>
            </div>
        </div>
    </main>


</body>
</html>
