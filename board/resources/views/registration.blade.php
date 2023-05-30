@extends('layout.layout')

@section('title', 'Registration')

@section('contents')
    <h1>REGISTRATION</h1>
    <p></p>
    @include('layout.errorsvalidate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="name">Name : </label>
        <input type="text" name="name" id="name">
        <p></p>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email">
        <p></p>
        <label for="password">Password : </label>
        <input type="password" name="password" id="password">
        <p></p>
        <label for="passwordchk">Password check : </label>
        <input type="password" name="passwordchk" id="passwordchk">
        <p></p>
        <button type="submit">Registration</button>
        <button type="button" onclick="location.href='{{route('users.login')}}'">Cancel</button>
    </form>
@endsection