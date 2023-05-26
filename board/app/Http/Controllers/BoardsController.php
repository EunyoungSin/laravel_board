<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        // $result = Boards::all();
        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
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

        // $boards = new Boards([
        //     'title' => $req->input('title')
        //     ,'content' => $req->input('content')
        // ]);
        // $boards->save();

        $result = Boards::find($id);
        $result->title = $request->title;
        $result->content = $request->content;
        $result->save();

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
}
