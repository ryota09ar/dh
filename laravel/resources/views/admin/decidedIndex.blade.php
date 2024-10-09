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
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/yearMonthSelect.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/admin/decidedIndex.css") }}?v={{ time() }}">
    <title>decidedIndex</title>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="{{ route("admin.menu") }}">管理者メニュー</a></li>
                <li><a href="#">シフト一覧</a></li>
            </ol>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="select-year-month">
                <form id="dataForm">
                    @csrf
                    <label class="select year">
                        <select id="year" name="year">
                        @for($i=$year-1;$i<=$year+1;$i++)
                            <option value={{$i}} {{ ($i==$year) ? "selected":"" }}>{{$i}}</option>
                        @endfor
                        </select>
                    </label>
                    <label for="year">年</label>

                    <label class="select month">
                        <select id="month" name="month">
                            @for($i=1;$i<=12;$i++)
                                <option value={{$i}} {{ ($i==$month) ? "selected":"" }}>{{$i}}</option>
                            @endfor
                        </select>
                    </label>
                    <label for="month">月</label>
                    <button type="submit" class="renew">更新</button>
                </form>

            </div>

            <div class="decidedIndex">
                <table class="decidedTable">
                    @for($i=1;$i<=$countOfDate; $i++)
                        <tr>
                            <th class="day">{{$i}}日 <span{!! ($daysOfWeek[$i]=="土") ? " class=\"Sat\"":(($daysOfWeek[$i]=="日") ? " class=\"Sun\"":"") !!}>{{$daysOfWeek[$i]}}</span></th>
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
                <form action="{{route("shiftDecided.excel")}}" method="post" class="excel-form">
                    @csrf
                    <input type="hidden" name="year" value={{ $year }}>
                    <input type="hidden" name="month" value={{ $month }}>
                    <button class="excel">Excel出力</button>
                </form>
            </div>
        </div>
    </main>


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
