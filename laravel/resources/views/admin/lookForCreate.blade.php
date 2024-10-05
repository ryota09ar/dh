<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/yearMonthSelect.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/admin/lookForCreate.css") }}?v={{ time() }}">
    <title>look-for</title>
    <script>
        window.addEventListener('beforeunload', e=> {
            e.preventDefault();
        });
    </script>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="{{ route("admin.menu") }}">管理者メニュー</a></li>
                <li><a href="#">シフト募集</a></li>
            </ol>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="select-year-month">
                <form id="dataForm">
                    @csrf
                    <label class="select-yearMonth">
                        <select id="year" name="year">
                        @for($i=$year-1;$i<=$year+1;$i++)
                            <option value={{$i}} {{ ($i==$year) ? "selected":"" }}>{{$i}}</option>
                        @endfor
                        </select>
                    </label>
                    <label for="year">年</label>

                    <label class="select-yearMonth">
                        <select id="month" name="month">
                        @for($i=1;$i<=12;$i++)
                            <option value={{$i}} {{ ($i==$month) ? "selected":"" }}>{{$i}}</option>
                        @endfor
                        </select>
                    </label>
                    <label for="month">月</label>
                    <button type="submit" class="renew">更新</button>
                </form>
                <form action="{{ route("shiftLookFor.confirm") }}" method="post" class="confirm_form">
                    @csrf
                    <input type="hidden" name="year" value={{ $year }}>
                    <input type="hidden" name="month" value={{ $month }}>
                    <button class="confirm_button">{{ (\App\Models\ConfirmedYearMonth::is_confirmed($year, $month) ? "確定済":"確定") }}</button>
                </form>
            </div>
            @if ($errors->has('yearMonth'))
                <div class="alert alert-danger">
                    {{ $errors->first('yearMonth') }}
                </div>
            @endif
            <div class="lookForShift">
                <form id="form" method="post">
                    @csrf
                    <input type="hidden" name="year" value={{ $year }}>
                    <input type="hidden" name="month" value={{ $month }}>

                    <table class="lookForTable">
                    @for($i=1;$i<=$countOfDate;$i++)
                        <tr>
                            <th>{{$i}}日 <span{!! ($daysOfWeek[$i]=="土") ? " class=\"Sat\"":(($daysOfWeek[$i]=="日") ? " class=\"Sun\"":"") !!}>{{$daysOfWeek[$i]}}</span></th>
                            @for($k=1;$k<=4;$k++)
                                <td>
                                    <label class="select-box">
                                        <select name="shift_{{$i}}_{{$k}}">
                                            <option value=0></option>
                                            @foreach($shift_contents as $shift_content)
                                                <option value={{$shift_content->id}} {{ ($shift_content->id==$lookForShiftsLoaded[$i][$k-1]) ? "selected":"" }}>{{$shift_content->place." ".$shift_content->time}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </td>
                            @endfor
                        </tr>
                    @endfor
                    </table>
                    <div class="buttons">
                        <div class="room"></div>
                        <div class="room">
                            <button onclick="submitForm('{{ route("shiftLookFor.store") }}')" class="complete">完了</button>
                        </div>
                        <div class="room">
                            <button onclick="submitForm('{{ route("shiftLookFor.excel") }}')" class="excel">Excel出力</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        function submitForm(action) {
            var form = document.getElementById('form');
            form.action = action;
            form.submit();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // フォームの送信イベントを処理
            $('#dateForm').on('submit', function(e) {
                e.preventDefault(); // フォームの通常の送信を防ぐ

                const year = $('#year').val();
                const month = $('#month').val();

                $.ajax({
                    url: '{{ route('shiftLookFor.create') }}', // ここでルートを指定
                    method: 'GET',
                    data: { year: year, month: month },
                    success: function(response) {
                        $('#lookForShift').html(response); // サーバーからのレスポンスでコンテンツを更新
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
