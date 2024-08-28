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
    <h1>シフト提出</h1>
    <form action="" method="post">
        @csrf
        <select id="year" name="year">
            @for($i=2024;$i<=2026;$i++)
                <option value={{$i}}>{{$i}}</option>
            @endfor
        </select>
        <label for="year">年</label>

        <select id="month" name="month">
        @for($i=1;$i<=12;$i++)
            <option value={{$i}}>{{$i}}</option>
        @endfor
        </select>
        <label for="month">月</label>

        <label for="shift">シフト選択</label>
        @for($k=1;$k<=31;$k++)
            <p>{{$k}}日</p>
            <select id="shift" name="{{"shift_content_".$k}}">
                <option value=0>なし</option>
                @foreach($shift_contents as $shift_content)
                    <option value={{$shift_content->id}}>{{$shift_content->place.$shift_content->time}}</option>
                @endforeach
            </select>
        @endfor
        <button>提出</button>
    </form>
</body>
</html>
