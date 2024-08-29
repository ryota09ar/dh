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
    <h1>シフト募集</h1>
    <form id="form" method="post">
        @csrf
        <h3>{{ $year }}年</h3>
        <h3>{{ $month }}月</h3>

        <input type="hidden" name="year" value={{ $year }}>
        <input type="hidden" name="month" value={{ $month }}>

        <h3>シフト選択</h3>
        @for($i=1;$i<=$countOfDate;$i++)
            <p>{{$i}}日 {{$daysOfWeek[$i]}}</p>
            @for($k=1;$k<=4;$k++)
                <select name="shift_{{$i}}_{{$k}}">
                    <option value=0></option>
                    @foreach($shift_contents as $shift_content)
                        <option value={{$shift_content->id}} {{ ($shift_content->id==$lookForShiftsLoaded[$i][$k-1]) ? "selected":"" }}>{{$shift_content->place." ".$shift_content->time}}</option>
                    @endforeach
                </select>
            @endfor
        @endfor
        <button onclick="submitForm('{{ route("shiftLookFor.store") }}')">完了</button>
        <button onclick="submitForm('{{ route("shiftLookFor.excel") }}')">Excel出力</button>
    </form>

    <script>
        function submitForm(action) {
            var form = document.getElementById('form');
            form.action = action;
            form.submit();
        }
    </script>

    <a href="{{ route("admin.menu") }}">メニューへ</a>
</body>
</html>
