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
    <h1>シフト編集</h1>
    <h3>{{ $year }}年</h3>
    <h3>{{ $month }}月</h3>
    @php
        $decideShifts = App\Models\DecideShift::whereYear('date',$year)->whereMonth('date',$month)->get();
    @endphp

    <form action="{{ route("shiftDecide.store") }}" method="post" id="checkboxForm">
        @csrf
        <input type="hidden" name="year" value={{ $year }}>
        <input type="hidden" name="month" value={{ $month }}>
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
                                        <input id="shift" type="checkbox" class="checkbox" name="decideShifts_{{ $i }}[]" data-option="{{ $requestShift->user_id }}"  value={{ $requestShift->id }}  {{ ($k) ? "checked":"" }}>{{$requestShift->user->name}}
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
                    <th>{{ $user->name }}</th>
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

</body>
</html>
