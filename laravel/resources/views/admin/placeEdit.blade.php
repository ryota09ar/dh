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
    <h1>シフト場所更新</h1>
    <form action="{{route("shiftPlace.update", ["id"=>$shift_content->id]) }}" method="post">
        @csrf
        <label for="place">場所</label>
        <input id="place" name="place" type="text" value="{{$shift_content->place}}">

        <select id="hour" name="hour">
            @for($i=0;$i<=23;$i++)
                <option value={{$i}} {{ (str_pad($i, 2, '0', STR_PAD_LEFT)==substr($shift_content->time, 0, 2)) ? "selected":"" }}>{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option>
            @endfor
        </select>
        <label for="hour">時</label>

        <select id="minute" name="minute">
            @for($i=0;$i<12;$i++)
                <option value={{$i*5}} {{ (str_pad($i*5, 2, '0', STR_PAD_LEFT)==substr($shift_content->time, 3, 2)) ? "selected":"" }}>{{str_pad($i*5, 2, '0', STR_PAD_LEFT)}}</option>
            @endfor
        </select>
        <label for="minute">分</label>
        <button>更新</button>
    </form>
</body>
</html>
