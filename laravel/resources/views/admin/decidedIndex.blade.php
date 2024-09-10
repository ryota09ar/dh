@php
    use App\Services\UserService;
@endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>decidedIndex</title>
</head>
<body>
<h1>シフト一覧</h1>
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

<table>
    @for($i=1;$i<=$countOfDate; $i++)
        <tr>
            <th>{{ $i }}</th>
            <th>{{ $daysOfWeek[$i] }}</th>
            @for($j=0;$j<4;$j++)
                @if($lookForShiftIdsLoaded[$i][$j]!=0)
                    <td>
                        {{ ($place=\App\Models\LookForShift::find($lookForShiftIdsLoaded[$i][$j])->shiftContent->place).($time=\App\Models\LookForShift::find($lookForShiftIdsLoaded[$i][$j])->shiftContent->time)}}
                        @foreach($decidedShifts as $decidedShift)
                            @if($decidedShift->place==$place && $decidedShift->time==$time && $decidedShift->date==\App\Models\LookForShift::find($lookForShiftIdsLoaded[$i][$j])->date)
                                {{ UserService::return_name($decidedShift->user_id) }}
                            @endif
                        @endforeach
                    </td>
                @else
                    <td></td>
                @endif
            @endfor
        </tr>
    @endfor
</table>
<form action="{{route("shiftDecided.excel")}}" method="post">
    @csrf
    <input type="hidden" name="year" value={{ $year }}>
    <input type="hidden" name="month" value={{ $month }}>
    <button>Excel出力</button>
</form>
<a href="{{ route("admin.menu") }}">メニューへ</a>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // フォームの送信イベントを処理
        $('#dateForm').on('submit', function (e) {
            e.preventDefault(); // フォームの通常の送信を防ぐ

            const year = $('#year').val();
            const month = $('#month').val();

            $.ajax({
                url: '{{ route('shiftDecide.create') }}', // ここでルートを指定
                method: 'GET',
                data: {year: year, month: month},
                success: function (response) {
                    $('#decideShift').html(response); // サーバーからのレスポンスでコンテンツを更新
                },
                error: function () {
                    alert('Error.');
                }
            });
        });
    });
</script>
</body>
</html>
