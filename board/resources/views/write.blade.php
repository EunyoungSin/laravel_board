<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    {{-- 에러가 있으면 실행 --}}
    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            <div>{{$error}}</div>
        @endforeach
    @endif

    <form action="{{route('boards.store')}}" method="post">
        @csrf
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title" value="{{old('title')}}">
        {{-- old() 세션에 있는 값을 불러옴. --}}
        <p></p>
        <label for="content">내용 : </label>
        <textarea name="content" id="content">{{old('content')}}</textarea>
        <p></p>
        <button type="submit">작성</button>
    </form>

</body>
</html>