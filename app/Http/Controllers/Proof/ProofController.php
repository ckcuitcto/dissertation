<?php

namespace App\Http\Controllers\Proof;

use App\Model\Proof;
use App\Model\Semester;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
class ProofController extends Controller
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
        $proofList = Proof::all();
        return view('proof.index', compact('proofList'));
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
//        $news = News::find($id);
//        return response()->json([
//            'news' => $news,
//            'status' => true
//        ], 200);
    }

    public function update(Request $request, $id)
    {
//        $validator = Validator::make($request->all(), [
//            'title' => 'required',
//            'content' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'status' => false,
//                'arrMessages' => $validator->errors()
//            ], 200);
//        } else {
//            $news = News::find($id);
//            if (!empty($news)) {
//                $news->title = $request->title;
//                $news->content = $request->content;
//                $news->save();
//                return response()->json([
//                    'news' => $news,
//                    'status' => true
//                ], 200);
//            }
//            return response()->json([
//                'status' => false
//            ], 200);
//        }

    }

    public function store(Request $request)
    {
       // dd($request->all());
       $validator = Validator::make($request->all(),
           [
               'name' => 'required'
           ],
           [
               'title.required' => 'Tiêu đề là bắt buộc'
           ]
       );

       if ($validator->fails()) {
           return response()->json([
               'status' => false,
               'arrMessages' => $validator->errors()
           ], 200);
       } else {

           $proof = new Proof();
           $proof->name = $request->name;
           $proof->semester_id = $request->semester_id;
           $proof->created_by = Auth::user()->Staff->id;

           $news->save();
           return response()->json([
               'proof' => $proof,
               'status' => true
           ], 200);
       }
    }

    public function destroy($id)
    {
//        $news = News::find($id);
//        if (!empty($news)) {
//            $news->delete();
//            return response()->json([
//                'news' => $news,
//                'status' => true
//            ], 200);
//        }
//        return response()->json([
//            'status' => false
//        ], 200);
    }
}
