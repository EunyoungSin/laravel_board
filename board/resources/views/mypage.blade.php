@extends('layout.layout')

@section('title', 'Login')

@section('contents')
    <h1>MY PAGE</h1>
    <p></p>
    @include('layout.errorsvalidate')
    <div>{!!session()->has('success') ? session('success') : ""!!}</div>
    <form action="{{route('users.mypage.post')}}" method="post">
        @csrf
        <label for="name">Name : </label>
        <input type="text" name="name" id="name" value="{{ count($errors) > 0 ? old('name') : $data->name }}">
        <p></p>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email" value="{{ count($errors) > 0 ? old('email') : $data->email }}">
        <p></p>
        <label for="bpassword">Before Password : </label>
        <input type="password" name="bpassword" id="bpassword">
        <p></p>
        <label for="apassword">After Password : </label>
        <input type="password" name="password" id="password">
        <p></p>
        <label for="passwordchk">After Password check: </label>
        <input type="password" name="passwordchk" id="passwordchk">
        <br><br>
        <button type="submit">수정</button>
        <button type="button" onclick="location.href='{{route('boards.index')}}'">취소</button>
        <button type="button" onclick="location.href='{{route('users.withdraw')}}'">탈퇴</button>
    </form>
    <br>
@endsection