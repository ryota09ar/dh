<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/admin/placeIndex.css") }}?v={{ time() }}">
    <title>placeIndex</title>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="{{ route("admin.menu") }}">管理者メニュー</a></li>
                <li><a href="#">シフト場所一覧</a></li>
            </ol>
        </div>
    </header>
    <main>
        <div class="container">
        @if ($errors->has('overCount'))
            <div class="alert alert-danger">
                {{ $errors->first('overCount') }}
            </div>
        @endif
            <div class="buttons">
            @foreach($shift_contents as $shift_content)
                <a href="{{route("shiftPlace.edit", ["id"=>$shift_content->id]) }}" class="button-51">{{$shift_content->place.$shift_content->time}}</a>
            @endforeach
            </div>
            <a href="{{route("shiftPlace.create")}}" class="addition">追加</a>
        </div>
    </main>

</body>
</html>
