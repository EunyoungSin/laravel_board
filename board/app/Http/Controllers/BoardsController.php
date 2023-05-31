<?php
/******************************************
 * Project Name : laravel_board
 * Directory    : Controllers
 * File Name    : BoardsController.php
 * History      : v001 0526 EY.Sin new
 *                v002 0530 EY.Sin 유효성 체크 추가
 *******************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; // v002 add
use App\Models\Boards;

// ls로 목록을 보고 api 요청이 왔을 때 method를 보고 판단.
// get일 경우에 단순 검색, post일 경우 수정. put이면 기존 데이터에 업데이트. delete로 오면 삭제.

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 로그인 체크 (미로그인시 로그인 페이지로 이동). 프로젝트할 땐 메소드로 만들기
        if(auth()->guest()) {
            return redirect()->route('users.login');
        }

        // $result = Boards::all();
        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])->orderBy('id', 'desc')->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 002 update start

        
        // return view('index');
        return view('write');
        // 002 update end
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req) // insert
    {
        // v002 add start
        $req->validate([ // validate는 자동으로 리다이렉트 해줌.
            'title' => 'required|between:3,30'
            ,'content' => 'required|max:2000'
        ]);
        // v002 add end

        // DB에서 불러오는 게 아니라, 새로운 엘로퀀트 모델을 생성하기 때문에 new로 시작함.
        $boards = new Boards([
            'title' => $req->input('title')
            ,'content' => $req->input('content')
        ]);
        $boards->save();
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id); // find 안에 쿼리가 다 들어가있음. show 안에서 select를 해서 화면에 뿌려줌.
        $boards->hits++;
        $boards->save();

        return view('detail')->with('data', Boards::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boards = Boards::find($id);
        return view('edit')->with('data', $boards); // find로 찾으면 return값이 false라서 false값에 대한 처리를 해야함.
    }

    /**
     * Update the specified resource in storage.
     *
     * 
     * 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // DB::table('Boards')->where('id', '=', $id)->update([
        //     'title' => $request->title
        //     ,'content' => $request->content
        // ]);

        // v002 add start
        // ID를 리퀘스트 객체에 머지. ID 유효성 검사.
        $arr = ['id' => $id];
        // $request->merge($arr); // request가 public이 아닌 protected 였으면 merge로만 가능.
        $request->request->add($arr); // request 객체에 $arr 배열을 추가하겠다.
        // v002 add end

        // 유효성 검사 방법 1
        $request->validate([ // validate는 자동으로 리다이렉트 해줌.
            'id'        => 'required|integer' // v002 add
            ,'title'    => 'required|between:3,30'
            ,'content'  => 'required|max:2000'
        ]);

        // 유효성 검사 방법 2
        // $validator = Validator::make(
        //     $request->only('id', 'title', 'content')
        //     ,[
        //         'id'        => 'required|integer'
        //         ,'title'    => 'required|between:3,30'
        //         ,'content'  => 'required|max:2000'
        //     ]
        // );

        // if($validator->fails()) { // 실패한 게 있으면 true, 없으면 fail 불린형으로 반환
        //     return redirect()
        //         ->back()
        //         ->withErrors($validator)
        //         ->withInput($request->only('title', 'content'));
        // }

        $result = Boards::find($id);
        $result->title = $request->title;
        $result->content = $request->content;
        $result->save();
        // 수정한 방법
        // $boards = new Boards([
        //     'title' => $req->input('title')
        //     ,'content' => $req->input('content')
        // ]);
        // $boards->save();


        return redirect('/boards/'.$id);
        // return redirect()->route('boards.show', ['board' => $id]);
        // 요청받은 URL과 표시해야하는 URL이 다를 경우 무조건 리다이렉트를 해줘야함.
        // show(상세)->edit(수정)->update(페이지x)->show(상세)
        // URL  : index  show  edit  update
        // view : list  detail edit    x
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Boards::find($id)->delete();
        return redirect('/boards');
    }

    // 로그인 체크 (미로그인시 로그인 페이지로 이동)
    // public function loginChk() {
    //     if(auth()->guest()) {
    //         return redirect()->route('users.login');
    //     }
    // }
}
