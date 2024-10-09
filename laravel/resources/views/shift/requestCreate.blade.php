<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/yearMonthSelect.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/checkbox.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/shift/request.css") }}?v={{ time() }}">
    <title>request</title>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="{{ route("user.home") }}">メニュー</a></li>
                <li><a href="#">シフト提出</a></li>
            </ol>
            <a href="{{route("logout")}}" class="logout">ログアウト</a>
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

            @if ($errors->has('yearMonth'))
                <div class="alert alert-danger">
                    {{ $errors->first('yearMonth') }}
                </div>
            @endif

            <div class="requestShift">
                <form action="{{ route("shiftRequest.store") }}" method="post">
                    @csrf
                    <label for="shift">シフト選択</label>
                    <input type="hidden" name="year" value={{$year}}>
                    <input type="hidden" name="month" value={{$month}}>
                    <table class="requestTable">
                    @for($k=1;$k<=$countOfDate;$k++)
                        <tr>
                            <th>{{$k}}日 <span{!! ($daysOfWeek[$k]=="土") ? " class=\"Sat\"":(($daysOfWeek[$k]=="日") ? " class=\"Sun\"":"") !!}>{{$daysOfWeek[$k]}}</span></th>
                            @foreach($lookForShiftIdsLoaded[$k] as $lookForShiftId)
                                @if($lookForShiftId==0)
                                    <td></td>
                                @else
                                    <td>
                                        <fieldset class="checkbox-3">
                                            <label>
                                                <input id="shift" type="checkbox" name="lookForShiftIds[]" value={{ $lookForShiftId }} {{ in_array($lookForShiftId, $requestShiftsId) ? 'checked' : '' }}>{{\App\Models\LookForShift::find($lookForShiftId)->shiftContent->place.\App\Models\LookForShift::find($lookForShiftId)->shiftContent->time}}
                                            </label>
                                        </fieldset>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endfor
                    </table>

                    <button class="submit_button">提出</button>
                </form>
            </div>
        </div>
    </main>


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
