<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
</head>
<body>
    <div>
    글번호 : {{$data->id}}
    <p></p>
    조회수 : {{$data->hits}}
    <p></p>
    등록일자 : {{$data->created_at}}
    <p></p>
    수정일자 : {{$data->updated_at}}
    <p></p>
    제목 : {{$data->title}}
    <p></p>
    내용 : {{$data->content}}
    <p></p>
    </div>
    <button type="button" onclick="location.href='{{route('boards.index')}}'">리스트 페이지로</button>
    <button type="button" onclick="location.href='{{route('boards.edit', ['board' => $data->id])}}'">수정 페이지로</button>
    {{-- 배열이니까 => 로 표시 --}}
    <form action="{{route('boards.show', ['board' => $data->id])}}" method="post">
        @csrf
        @method('delete')
        <button type="submit">삭제</button>
    </form>
</body>
</html>