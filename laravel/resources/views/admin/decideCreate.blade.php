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
    <title>decideCreate</title>
</head>
<body>
    <h1>シフト編集</h1>
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

    <div class="decideShift">
        <form action="{{ route("shiftDecide.store") }}" method="post" id="checkboxForm">
            @csrf
            <input type="hidden" name="year" value={{ $year }}>
            <input type="hidden" name="month" value={{ $month }}>
            @php
                $decideShifts = App\Models\DecideShift::whereYear('date',$year)->whereMonth('date',$month)->get();
            @endphp
            <label for="shift"></label>
            <table>
                @for($i=1;$i<=$countOfDate;$i++)
                    <tr>
                        <th>{{ $i }}</th>
                        <th>{{ $daysOfWeek[$i] }}</th>
                        @for($j=0;$j<4;$j++)
                            @if($lookForShiftIdsLoaded[$i][$j]==0)
                                <td></td>
                            @else
                                <td>
                                    {{ \App\Models\LookForShift::find($lookForShiftIdsLoaded[$i][$j])->shiftContent->place }}
                                    <br>
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
                                            <input id="shift" type="checkbox" class="checkbox" name="decideShifts_{{ $i }}[]" data-option="{{ $requestShift->user_id }}"  value={{ $requestShift->id }}  {{ ($k) ? "checked":"" }}>{{UserService::return_name($requestShift->user_id)}}
                                        @endif
                                    @endforeach
                                </td>
                            @endif
                        @endfor
                    </tr>
                @endfor
                <tr></tr>
            </table>
            <table>
                <thead>
                <tr>
                    <th></th>
                    @foreach($users=\App\Models\User::all() as $user)
                        <th>{{ UserService::return_name($user->id) }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>希望数</th>
                    @foreach($users as $user)
                        <th>{{ \App\Models\RequestShift::whereYear('date',$year)->whereMonth('date',$month)->where("user_id", $user->id)->count() }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th>実施数</th>
                    @foreach($users as $user)
                        <th><span id="checkedUser{{ $user->id }}Count">0</span></th>
                    @endforeach
                </tr>
                </tbody>
            </table>
            <button>決定</button>
        </form>
    </div>
    <a href="{{ route("admin.menu") }}">メニューへ</a>


    <script>
        // チェックボックスのフォーム要素を取得
        const checkboxForm = document.getElementById('checkboxForm');
        // Option 1 のチェックされている数を表示する要素を取得
        @foreach($users as $user)
            const checkedUser{{ $user->id }}CountDisplay = document.getElementById('checkedUser{{ $user->id }}Count');
        @endforeach
        // チェックボックスが変更されるたびにカウントを更新する関数
        function updateCheckedUserCount() {
            // フォーム内のすべてのOption 1のチェックボックスを取得
            @foreach($users as $user)
                const user{{ $user->id }}Checkboxes = checkboxForm.querySelectorAll('.checkbox[data-option="{{ $user->id }}"]');
                // チェックされたOption 1の数を表示
                checkedUser{{ $user->id }}CountDisplay.textContent = Array.from(user{{ $user->id }}Checkboxes).filter(checkbox => checkbox.checked).length.toString();
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
