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
    <h1>年月選択</h1>
    <form action="{{ route("shiftLookFor.create") }}" method="get">
        @csrf
        <select id="year" name="year">
            @for($i=(int)date("Y")-1;$i<=(int)date("Y")+1;$i++)
                <option value={{$i}} {{ ($i==date("Y")) ? "selected":"" }}>{{$i}}</option>
            @endfor
        </select>
        <label for="year">年</label>

        <select id="month" name="month">
            @for($i=1;$i<=12;$i++)
                <option value={{$i}} {{ ($i==(int)date("n")+1) ? "selected":"" }}>{{$i}}</option>
            @endfor
        </select>
        <label for="month">月</label>
        <button>選択</button>
    </form>
</body>
</html>
