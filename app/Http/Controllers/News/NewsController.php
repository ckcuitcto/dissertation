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
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $newsList = News::all();
        $faculties = Faculty::all();
        return view('news.index', compact('newsList','faculties'));
    }

     public function show($id)
     {
//         $news = News::find($id);
//         return view('news.index', compact('news'));
     }

    public function edit($id)
    {
        $news = News::find($id);
        return response()->json([
            'news' => $news,
            'status' => true
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $news = News::find($id);
            if (!empty($news)) {
                $news->title = $request->title;
                $news->content = $request->content;
                $news->save();
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

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), 
        [
            'title' => 'required',
            'content' => 'required',
        ],
        [
            'title.required' => 'Tiêu đề là bắt buộc',
            'content.required' => 'Nội dung là bắt buộc',
        ]
        // tu gio lam them cai nay nua
    );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {

            $news = new News();
            $news->title = $request->title;
            $news->content = $request->content;
            $news->faculty_id = $request->faculty_id;
            $news->created_by = Auth::user()->Staff->id;
  
            $news->save();
            return response()->json([
                'news' => $news,
                'status' => true
            ], 200);
        }
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
