<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset("/css/header.css") }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset("/css/admin/placeEdit.css") }}?v={{ time() }}">
    <title>placeCreate</title>
</head>
<body>
    <header>
        <div class="container">
            <ol class="breadcrumb-004">
                <li><a href="{{ route("admin.menu") }}">管理者メニュー</a></li>
                <li><a href="{{ route("shiftPlace.index") }}">シフト場所一覧</a></li>
                <li><a href="#">シフト場所追加</a></li>
            </ol>
        </div>
    </header>
    <main>
        <div class="container">
            <form action="{{route("shiftPlace.store")}}" method="post">
                @csrf
                <div class="edit">
                    <label for="place">場所</label>
                    <input id="place" name="place" class="text-box" type="text" placeholder="例) 所沢駅">

                    <label class="select-box">
                        <select id="hour" name="hour">
                            @for($i=0;$i<=23;$i++)
                                <option value={{$i}}>{{str_pad($i, 2, '0', STR_PAD_LEFT)}}</option>
                            @endfor
                        </select>
                    </label>
                    <label for="hour">時</label>

                    <label class="select-box">
                        <select id="minute" name="minute">
                            @for($i=0;$i<12;$i++)
                                <option value={{$i*5}}>{{str_pad($i*5, 2, '0', STR_PAD_LEFT)}}</option>
                            @endfor
                        </select>
                    </label>
                    <label for="minute">分</label>
                </div>
                <button>追加</button>
            </form>
        </div>
    </main>

</body>
</html>
