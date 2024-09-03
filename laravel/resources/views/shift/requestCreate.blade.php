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
    <p>{{$user->name}}さん</p>
    <h3>{{$year}}年</h3>
    <h3>{{$month}}月</h3>

    <form action="{{ route("shiftRequest.store") }}" method="post">
        @csrf
        <label for="shift">シフト選択</label>
        <input type="hidden" name="year" value={{$year}}>
        <input type="hidden" name="month" value={{$month}}>

        @for($k=1;$k<=$countOfDate;$k++)
            <p>{{$k}}日 {{$daysOfWeek[$k]}}</p>
            @foreach($lookForShiftsLoaded[$k] as $lookForShift)
                <input id="shift" type="checkbox" name="lookForShiftIds[]" value={{ $lookForShift->id }} {{ in_array($lookForShift->id, $requestShiftsId) ? 'checked' : '' }}>
                <p>{{$lookForShift->shiftContent->place.$lookForShift->shiftContent->time}}</p>
            @endforeach
        @endfor
        <button>提出</button>
    </form>
    <a href="{{ route("user.home") }}">メニューへ</a>
</body>
</html>
