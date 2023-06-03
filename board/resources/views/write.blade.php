<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    @include('layout.errorsvalidate')
    <form action="{{route('boards.store')}}" method="post">
        @csrf
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title" value="{{old('title')}}">
        {{-- old() 세션에 있는 값을 불러옴. PHP는 다이나믹 프로퍼티를 써야했지만 라라벨은 old만 쓰면 됨--}}
        <p></p>
        <label for="content">내용 : </label>
        <textarea name="content" id="content">{{old('content')}}</textarea>
        <p></p>
        <button type="submit">작성</button>
    </form>

</body>
</html>