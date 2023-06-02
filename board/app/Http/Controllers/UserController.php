<?php
/******************************************
 * Project Name : laravel_board
 * Directory    : Controllers
 * File Name    : UserController.php
 * History      : v001 0530 EY.Sin new
 *******************************************/

// 관리자, 일반회원, 게스트별 권한 분리

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    function login() {

        $arr['key'] = 'test';
        $arr['kim'] = 'park';

        Log::emergency('emergency', $arr);
        Log::alert('alert', $arr);
        Log::critical('critical', $arr);
        Log::error('error', $arr);
        Log::warning('warning', $arr);
        Log::notice('notice', $arr);
        Log::info('info', $arr);
        Log::debug('debug', $arr);
        // 서비스 개시 때에는 로그 레벨을 debug 윗단계로 올려두기.

        return view('login');
    }

    function loginpost(Request $req) {

        Log::debug('로그인 시작');
        //유효성 체크
        $req->validate([ // validate는 자동으로 리다이렉트 해줌.
            'email'    => 'required|email|max:100'
            ,'password'  => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);
        
        Log::debug('유효성 OK');

        // 유저정보 습득
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))) {
            Log::debug($req->password . ' : '. $user->password);
            $error = '아이디와 비밀번호를 확인해주세요.';
            return redirect()->back()->with('error', $error);
        }

        // 유저 인증작업
        Auth::login($user); // 테스트시 비활성화 하고 테스트하면 됨.
        if(Auth::check()) {
            session($user->only('id')); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index'));
        } else {
            $error = '인증작업 에러';
            return redirect()->back()->with('error', $error);
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
            ,'password'  => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // $data['name'] = $req->input('name'); // 밑의 방법과 동일함.
        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);

        $user = User::create($data); // insert. create ORM 모델
        if(!$user) {
            $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다.<br>잠시 후에 다시 회원가입을 시도해 주십시오.';
        return redirect()
            ->route('users.registration')
            ->with('error', $error);
        }

        // 회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해주십시오.');
    }

    function logout() {
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }

    // 회원탈퇴 
    function withdraw() {
        $id = session('id');
        $result = User::destroy($id); // destroy 에러 났을 때 에러 핸들링 써서 예외 처리 하기
        Session::flush(); // 세션 파기
        Auth::logout(); // 로그아웃
        return redirect()->route('users.login');
    }

    // 회원정보 수정
    function mypage() {
        // $id = session('id'); // id를 세션에다 저장
        // $user = User::find($id);
        $user = User::find(Auth::User()->id); // id를 DB서버에 질의. db에 저장된 유저수가 많으면 속도가 느림.
        return view('mypage')->with('data', $user);
    }

    function mypagepost(Request $req) {
        $arrKey = []; // 수정할 항목을 배열에 담는 변수

        $baseUser = User::find(Auth::User()->id); // 기존 데이터 획득

        // 기존 패스워드 체크
        if(!Hash::check($req->bpassword, $baseUser->password)) {
            return redirect()->back()->with('error', '기존 비밀번호를 확인해 주세요.');
        }

        // 수정할 항목을 배열에 담는 처리
        // ->재사용성을 위해 루프 돌리면서 필요한 정보만 담아 배열을 만들어 리턴한 것.
        if($req->name !== $baseUser->name) {
            $arrKey[] = 'name';
        }

        if($req->email !== $baseUser->email) {
            $arrKey[] = 'email';
        }

        if(isset($req->password)) {
            $arrKey[] = 'password';
        }

        //유효성 체크를 하는 모든 항목 리스트
        $chkList= [
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30' // regex:정규식. 한글 1자 이상 포함 및 글자 수 2~30
            ,'email'    => 'required|email|max:100'
            ,'bpassword' => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
            ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ];
        
        // 유효성 체크할 항목 셋팅하는 처리 (리소스를 줄이기 위해 최대한 루프를 줄였음.)
        $arrchk['bpassword'] = $chkList['bpassword'];
        foreach($arrKey as $val) {
            $arrchk[$val] = $chkList[$val];
        }

        // return var_dump($arrchk); // 많은 값을 담으면 var_dump로 체크함

        // 유효성 체크
        $req->validate($arrchk); // validate는 자동으로 리다이렉트 해줌.
        
        foreach($arrKey as $val) {
            if($val === 'password') {
                $val = Hash::make($req->$val);
                continue;
            }
            $baseUser->$val = $req->$val;
        }
        $baseUser->save(); // update

        // $id = session('id');
        // $result = User::find($id);
        // $result->name = $req->name;
        // $result->email = $req->email;
        // $result->password = Hash::make($req->password);
        // $result->save();

        // 회원정보 수정완료 후 리스트 페이지로 이동
        return redirect()
        ->route('boards.index')
        ->with('success', '회원정보 수정을 완료 했습니다.');
    }
    // if (!$req->user()) {
    //     $error = '인증되지 않은 사용자입니다. 다시 로그인해 주세요.';
    //     return redirect()
    //         ->route('users.login')
    //         ->with('error', $error);
    // }
}
