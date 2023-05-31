<h2>Header</h2>

{{-- 로그인 상태. 로그인된 유저일 때만 실행됨 --}}
@auth
    <div><a href="{{route('users.logout')}}">로그아웃</a>
    <a href="{{route('users.mypage')}}">회원정보 수정</a></div>
@endauth

{{-- 미인증(비로그인) 상태. --}}
@guest
    <div><a href="{{route('users.login')}}">로그인</a></div>
@endguest
<hr>