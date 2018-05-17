<?php

namespace App\Http\Controllers\Proof;

use App\Model\MarkTime;
use App\Model\Proof;
use App\Model\Semester;
use App\Http\Controllers\Controller;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $userLogin = Auth::user();
        // nếu role vào k phải là học sinh
        if($userLogin->role_id >= 2) {
            return redirect()->back();
        }

        // xác định role để lấy thời gian chấm.
        $role = $userLogin->role_id;
        // nếu user đăng nhạp là ban cán sự lớp thì sẽ lấy thời gian chấm của sinh viên bình thường
        // nếu lấy theo role của ban cán sự lớp thì ban cán sự lớp có thể xóa file quá thời gian chấm
        if($userLogin->role_id == 2) {
            $role = 1;
        }

        $proofList = DB::table('proofs')
            ->leftJoin('evaluation_criterias','proofs.evaluation_criteria_id','=','evaluation_criterias.id')
            ->leftJoin('semesters','semesters.id','=','proofs.semester_id')
            ->leftJoin('mark_times','mark_times.semester_id','=','semesters.id')
            ->select('evaluation_criterias.content','evaluation_criterias.detail','proofs.*','semesters.year_from','semesters.year_to','semesters.term','mark_times.mark_time_start','mark_times.mark_time_end')
            ->where([
                ['proofs.created_by','=', $userLogin->Student->id],
                ['mark_times.role_id','=', $role]
            ])
            ->orderBy('proofs.id')
            ->get();
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

           $proof->save();
           return response()->json([
               'proof' => $proof,
               'status' => true
           ], 200);
       }
    }

    public function destroy($id)
    {
        $proof = Proof::find($id);
//        $this->authorize($proof,'delete');

        if (!empty($proof)) {
            $proof->delete();
            //sau khi xóa học kì thì cũng xóa form đánh giá
            return response()->json([
                'proof' => $proof,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }

    public function updateValidProofFile(Request $request, $id){

        if($request->valid == 0) {
            $validator = Validator::make($request->all(), [
                'note' => 'required',
            ], [
                'note.required' => "Vui lòng nhập lí do File không phù hợp",
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'arrMessages' => $validator->errors()
                ], 200);
            }
        }

        $proof = Proof::find($id);
        if(!empty($proof)){
            $proof->valid = $request->valid;
            $proof->note = $request->note;
            $proof->save();
            return response()->json([
                'proof' => $proof,
                'status' => true
            ],200);
        }
        return response()->json([
            'status' => false
        ],200);
    }

    public function getProofById($id){
        $proof = Proof::find($id);
        return response()->json([
            'proof' => $proof,
            'status' => true
        ],200);
    }

    public static function checkTimeMark($mark_time_start,$mark_time_end){

        $valid1 = strtotime($mark_time_start) <= strtotime(Carbon::now()->format('Y-m-d'));
        $valid2 = strtotime($mark_time_end) >= strtotime(Carbon::now()->format('Y-m-d'));
        if($valid1 AND $valid2)
        {
            return true;
        }
        return false;
    }
}
