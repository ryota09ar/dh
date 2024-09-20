<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/admin/menu.css") }}?v={{ time() }}">
    <title>admin_menu</title>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="#">管理者メニュー</a></li>
            </ol>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="menu">
                <div class="menu-wrapper">
                    <a href="{{ route("shiftLookFor.create", ['year' => (((int)date("n")==12) ? (int)date("Y")+1:(int)date("Y")), 'month' => (int)date("n")+1]) }}">シフト募集</a>
                    <a href="{{ route("shiftDecide.create", ['year' => (((int)date("n")==12) ? (int)date("Y")+1:(int)date("Y")), 'month' => (int)date("n")+1]) }}">シフト編集</a>
                    <a href="{{ route("shiftDecided.index", ['year' => (((int)date("n")==12) ? (int)date("Y")+1:(int)date("Y")), 'month' => (int)date("n")+1]) }}">シフト一覧</a>
                    <a href="{{ route("shiftPlace.index") }}">シフト場所編集</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
