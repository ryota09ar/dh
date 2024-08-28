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
    <h1>シフト場所編集</h1>
    @foreach($shift_contents as $shift_content)
        <a href="{{route("shiftPlace.edit", ["id"=>$shift_content->id]) }}">{{$shift_content->place.$shift_content->time}}</a>
    @endforeach
    <a href="{{route("shiftPlace.create")}}">追加</a>
    <a href="{{route("admin.menu")}}">メニューへ</a>
</body>
</html>
