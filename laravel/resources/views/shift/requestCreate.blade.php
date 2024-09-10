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
    <p>{{$user->family_name.$user->first_name}}さん</p>
    <form id="dataForm">
        @csrf
        <select id="year" name="year">
            @for($i=$year-1;$i<=$year+1;$i++)
                <option value={{$i}} {{ ($i==$year) ? "selected":"" }}>{{$i}}</option>
            @endfor
        </select>
        <label for="year">年</label>

        <select id="month" name="month">
            @for($i=1;$i<=12;$i++)
                <option value={{$i}} {{ ($i==$month) ? "selected":"" }}>{{$i}}</option>
            @endfor
        </select>
        <label for="month">月</label>
        <button type="submit">更新</button>
    </form>

    <div class="requestShift">
        <form action="{{ route("shiftRequest.store") }}" method="post">
            @csrf
            <label for="shift">シフト選択</label>
            <input type="hidden" name="year" value={{$year}}>
            <input type="hidden" name="month" value={{$month}}>

            @for($k=1;$k<=$countOfDate;$k++)
                <p>{{$k}}日 {{$daysOfWeek[$k]}}</p>
                @foreach($lookForShiftsLoaded[$k] as $lookForShift)
                    <input id="shift" type="checkbox" name="lookForShiftIds[]" value={{ $lookForShift->id }} {{ in_array($lookForShift->id, $requestShiftsId) ? 'checked' : '' }}>
                    {{$lookForShift->shiftContent->place.$lookForShift->shiftContent->time}}
                @endforeach
            @endfor
            <button>提出</button>
        </form>
    </div>
    <a href="{{ route("user.home") }}">メニューへ</a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // フォームの送信イベントを処理
            $('#dateForm').on('submit', function(e) {
                e.preventDefault(); // フォームの通常の送信を防ぐ

                const year = $('#year').val();
                const month = $('#month').val();

                $.ajax({
                    url: '{{ route('shiftRequest.create') }}', // ここでルートを指定
                    method: 'GET',
                    data: { year: year, month: month },
                    success: function(response) {
                        $('#requestShift').html(response); // サーバーからのレスポンスでコンテンツを更新
                    },
                    error: function() {
                        alert('Error.');
                    }
                });
            });
        });
    </script>
</body>
</html>
