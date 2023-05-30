<?php
/******************************************
 * Project Name : laravel_board
 * Directory    : Controllers
 * File Name    : UserController.php
 * History      : v001 0530 EY.Sin new
 *******************************************/


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    function login() {
        return view('login');
    }

    function loginpost(Request $req) {
        //유효성 체크
        $req->validate([ // validate는 자동으로 리다이렉트 해줌.
            'email'    => 'required|email|max:100'
            ,'password'  => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);
        
        // 유저정보 습득
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))) {
            $errors[] = '아이디와 비밀번호를 확인해주세요.';
            return redirect()->back()->with('errors', collect($errors));
        }

        // 유저 인증작업
        Auth::login($user); // 테스트시 비활성화 하고 테스트하면 됨.
        if(Auth::check()) {
            session([$user->only('id')]); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index'));
        } else {
            $errors[] = '인증작업 에러';
            return redirect()->back()->with('errors', collect($errors));
        }
    }

    function registration() {
        return view('registration');
    }

    function registrationpost(Request $req) {
        //유효성 체크
        $req->validate([ // validate는 자동으로 리다이렉트 해줌.
            'name'        => 'required|regex:/^[가-힣]+$/|min:2|max:30' // regex:정규식. 한글 1자 이상 포함 및 글자 수 2~30
            ,'email'    => 'required|email|max:100'
            ,'password'  => 'required_with:passwordchk|same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // $data['name'] = $req->input('name'); // 밑의 방법과 동일함.
        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);

        $user = User::create($data); // insert
        if(!$user) {
            $errors[] = '시스템 에러가 발생하여, 회원가입에 실패했습니다.';
            $errors[] = '잠시 후에 다시 회원가입을 시도해 주십시오.';
        return redirect()
            ->route('users.registration')
            ->with('errors', collect($errors));
        }

        // 회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해주십시오.');
    }
}
