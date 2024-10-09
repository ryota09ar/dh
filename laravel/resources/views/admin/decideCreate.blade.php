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
    <link rel="stylesheet" href="{{ asset("/css/checkbox.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/admin/decideCreate.css") }}?v={{ time() }}">

    <title>decideCreate</title>
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
                <li><a href="#">シフト編集</a></li>
            </ol>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="select-year-month">
                <form id="dataForm" class="select-form">
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
                <form action="{{ route("shiftDecide.expiration") }}" method="post" class="expire_form">
                    @csrf
                    <input type="hidden" name="year" value={{ $year }}>
                    <input type="hidden" name="month" value={{ $month }}>
                    <button class="expire_button">{{ (\App\Models\ExpiredYearMonth::is_expired($year, $month) ? "締切済":"締切") }}</button>
                </form>
            </div>
            @if ($errors->has('yearMonth'))
                <div class="alert alert-danger">
                    {{ $errors->first('yearMonth') }}
                </div>
            @endif
            <div class="decideShift">
                <form action="{{ route("shiftDecide.store") }}" method="post" id="checkboxForm">
                    @csrf
                    <input type="hidden" name="year" value={{ $year }}>
                    <input type="hidden" name="month" value={{ $month }}>
                    @php
                        $decideShifts = App\Models\DecideShift::whereYear('date',$year)->whereMonth('date',$month)->get();
                    @endphp
                    <label for="shift"></label>
                    <table class="decideTable">
                    @for($i=1;$i<=$countOfDate;$i++)
                        <tr>
                            <th>{{$i}}日 <span{!! ($daysOfWeek[$i]=="土") ? " class=\"Sat\"":(($daysOfWeek[$i]=="日") ? " class=\"Sun\"":"") !!}>{{$daysOfWeek[$i]}}</span></th>
                            @for($j=0;$j<4;$j++)
                                @if($lookForShiftIdsLoaded[$i][$j]==0)
                                    <td></td>
                                @else
                                    <td>
                                        {{ \App\Models\LookForShift::find($lookForShiftIdsLoaded[$i][$j])->shiftContent->place." ".\App\Models\LookForShift::find($lookForShiftIdsLoaded[$i][$j])->shiftContent->time }}
                                        <br>
                                        <fieldset class="checkbox-3">
                                        @foreach($requestShiftsLoaded[$i] as $requestShift)
                                            @if($lookForShiftIdsLoaded[$i][$j]==$requestShift->look_for_shift_id)
                                                @php
                                                    $k=false;
                                                    foreach ($decideShifts as $decideShift){
                                                        if ($decideShift->user_id==$requestShift->user_id && $decideShift->date==$requestShift->date
                                                            && $decideShift->place==$requestShift->lookForShift->shiftContent->place && $decideShift->time==$requestShift->lookForShift->shiftContent->time){
                                                            $k=true;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <label>
                                                    <input id="shift" type="checkbox" class="checkbox" name="decideShifts_{{ $i }}[]" data-option="{{ $requestShift->user_id }}"  value={{ $requestShift->id }}  {{ ($k) ? "checked":"" }}>{{UserService::return_name($requestShift->user_id)}}
                                                </label>
                                            @endif
                                        @endforeach
                                        </fieldset>
                                    </td>
                                @endif
                            @endfor
                        </tr>
                    @endfor
                    </table>
                    <table class="countTable">
                        <thead>
                        <tr>
                            <th></th>
                            @foreach($requestedUsers as $user)
                                <th>{{ UserService::return_name($user->first()->id) }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>希望数</th>
                            @foreach($requestedUsers as $user)
                                <th>{{ \App\Models\RequestShift::whereYear('date',$year)->whereMonth('date',$month)->where("user_id", $user->first()->id)->count() }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th>実施数</th>
                            @foreach($requestedUsers as $user)
                                <th><span id="checkedUser{{ $user->first()->id }}Count">0</span></th>
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                    <button class="submit_button">決定</button>
                </form>
            </div>
        </div>
    </main>


    <script>
        // チェックボックスのフォーム要素を取得
        const checkboxForm = document.getElementById('checkboxForm');
        // Option 1 のチェックされている数を表示する要素を取得
        @foreach($requestedUsers as $user)
            const checkedUser{{ $user->first()->id }}CountDisplay = document.getElementById('checkedUser{{ $user->first()->id }}Count');
        @endforeach
        // チェックボックスが変更されるたびにカウントを更新する関数
        function updateCheckedUserCount() {
            // フォーム内のすべてのOption 1のチェックボックスを取得
            @foreach($requestedUsers as $user)
                const user{{ $user->first()->id }}Checkboxes = checkboxForm.querySelectorAll('.checkbox[data-option="{{ $user->first()->id }}"]');
                // チェックされたOption 1の数を表示
                checkedUser{{ $user->first()->id }}CountDisplay.textContent = Array.from(user{{ $user->first()->id }}Checkboxes).filter(checkbox => checkbox.checked).length.toString();
            @endforeach
        }
        // チェックボックスの状態が変わったときに関数を呼び出す
        checkboxForm.addEventListener('change', updateCheckedUserCount);
        updateCheckedUserCount();

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
                    url: '{{ route('shiftDecide.create') }}', // ここでルートを指定
                    method: 'GET',
                    data: { year: year, month: month },
                    success: function(response) {
                        $('#decideShift').html(response); // サーバーからのレスポンスでコンテンツを更新
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
