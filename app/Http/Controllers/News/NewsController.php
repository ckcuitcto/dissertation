<?php

namespace App\Http\Controllers\News;

use App\Model\Faculty;
use App\Model\News;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('aut thêh');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $newsList = News::all();

        return view('news.index', compact('newsList', 'faculties'));
    }

    public function create()
    {
        $faculties = Faculty::all();
        return view('news.add', compact('faculties'));
    }

    public function show($title,$id)
    {
        if(!is_numeric($id)){
            $id = explode('-',$id);
            $id = $id[count($id)-1];
        }
        $news = News::find($id);
        if(!empty($news)){
            return view('news.show', compact('news'));
        }
        return redirect()->back();
    }

    public function edit($id)
    {
        $news = News::find($id);
        if(!empty($news)){
            $faculties = Faculty::all();
            return view('news.edit', compact('faculties','news'));
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,
            [
                'title' => 'required',
                'news_content' => 'required',
            ],
            [
                'title.required' => "Vui lòng nhập tiêu đề",
                'news_content.required' => 'Vui lòng nhập nội dung',
            ]
        );

        $news = News::find($id);
        if (!empty($news)) {
            $news->title = $request->title;
            $news->content = $request->news_content;
            $news->faculty_id = $request->faculty_id;
//            $news->created_by = Auth::user()->Staff->id;
            $news->save();
            return redirect()->route('news')->with('success','Sửa tin tức thành công!');
        }
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'title' => 'required',
                'news_content' => 'required',
            ],
            [
                'title.required' => "Vui lòng nhập tiêu đề",
                'news_content.required' => 'Vui lòng nhập nội dung',
            ]
        );
        $news = new News();
        $news->title = $request->title;
        $news->content = $request->news_content;
        if($request->faculty_id) {
            $news->faculty_id = $request->faculty_id;
        }
        if(!empty(Auth::user()->Staff->id)){
            $news->created_by =Auth::user()->Staff->id;
        }else{
            $news->created_by =Auth::user()->Student->id;
        }

        $news->save();

        return redirect()->route('news')->with('success','Thêm tin tức thành công!');
    }

    public function destroy($id)
    {
        $news = News::find($id);
        if (!empty($news)) {
            $news->delete();
            return response()->json([
                'news' => $news,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }
}
