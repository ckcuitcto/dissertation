<?php

namespace App\Http\Controllers\News;

use App\Model\News;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('news.index', compact('newsList'));
    }

    public function show($id)
    {
        $news = News::find($id);
        return view('news.index', compact('news'));
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
            'ordinal_display' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        }

        $news = News::find($id);
        if (!empty($news)) {
            $news->title = $request->title;
            $news->ordinal_display = $request->ordinal_display;
            
            $semester->term = $request->term;
            $semester->save();
            return response()->json([
                'news' => $news,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'ordinal_display' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $news = new News();
            $news->title = $request->title;
            $news->ordinal_display = $request->ordinal_display;
            
            $news->term = $request->term;
            $news->save();           

            return response()->json([
                'news' => $news,
                'status' => true
            ], 200);
        }
    }
}
