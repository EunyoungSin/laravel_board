<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    <form action="{{route('boards.store')}}" method="post">
        @csrf
        @method('put')
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title">
        <p></p>
        <label for="content">내용 : </label>
        <textarea name="content" id="content"></textarea>
        <p></p>
        <button type="submit">작성</button>
    </form>

</body>
</html>