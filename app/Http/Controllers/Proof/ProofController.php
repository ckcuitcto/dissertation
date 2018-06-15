<?php

namespace App\Http\Controllers\Proof;

use App\Model\EvaluationCriteria;
use App\Model\MarkTime;
use App\Model\Notification;
use App\Model\Proof;
use App\Model\Semester;
use App\Http\Controllers\Controller;
use App\Model\Student;
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
        $userLogin = $this->getUserLogin();
        // nếu role vào k phải là học sinh
        if($userLogin->Role->weight >= ROLE_COVANHOCTAP) {
            return view('errors.403');
        }

        // xác định role để lấy thời gian chấm.
        // nếu user đăng nhạp là ban cán sự lớp thì sẽ lấy thời gian chấm của sinh viên bình thường
        // nếu lấy theo role của ban cán sự lớp thì ban cán sự lớp có thể xóa file quá thời gian chấm


        // luôn lấy theo thời gian của sinh viên
        $proofList = DB::table('proofs')
            ->leftJoin('evaluation_criterias','proofs.evaluation_criteria_id','=','evaluation_criterias.id')
            ->leftJoin('semesters','semesters.id','=','proofs.semester_id')
            ->leftJoin('mark_times','mark_times.semester_id','=','semesters.id')
            ->select('evaluation_criterias.content','evaluation_criterias.detail','proofs.*','semesters.year_from','semesters.year_to','semesters.term','mark_times.mark_time_start','mark_times.mark_time_end')
            ->where([
                ['proofs.created_by','=', $userLogin->Student->id],
                ['mark_times.role_id','=', $userLogin->Role->id ]
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

        // = 0 là k hợp lệ
        // 1 là hợp lệ
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

            //nếu sửa đổi trạng thái minh chứng thì tạo thông báo gửi đến sinh viên, cố vấn học tập, phòng CTSV
            // nếu thay đổi trạng thái thì mới tạo thông báo
            if($proof->valid != $request->valid){
                $student = Student::find($proof->created_by); // sinh viên chủ của minh chứng
                if(!empty($student)){

                    $semester = Semester::find($proof->semester_id);
                    $evaluationCriterias = EvaluationCriteria::find($proof->evaluation_criteria_id);

                    $notifications = new Notification();
                    $title = "Sửa trạng thái minh chứng của sinh viên: <b>".$student->User->users_id."-".$student->User->name ."</b> thuộc lớp: ".$student->Classes->name;
                    $notifications->title = $title;
                    $notifications->created_by = Auth::user()->Staff->id;
                    if($request->valid != 0){
                        $notifications->content = $title. " từ $proof->valid thành $request->valid của tiêu chí $evaluationCriterias->content .<br>
                         Cố vấn học tập vui lòng vào kiểm tra lại file minh chứng của sinh viên và chỉnh sửa điểm phù hợp!<br>
                         <b>Học kì $semester->term Năm học $semester->year_from - $semester->year_to.</b> <br>
                         ";
                    }else{
                        $notifications->content = $title. " từ $proof->valid thành $request->valid của tiêu chí $evaluationCriterias->content .Với nội dung:<b>$request->note.</b> 
                        Cố vấn học tập vui lòng vào kiểm tra lại file minh chứng của sinh viên và chỉnh sửa điểm phù hợp!<br>
                         <b>Học kì $semester->term Năm học $semester->year_from - $semester->year_to.</b> <br>
                        ";
                    }

                    //danh sách Id của các user sẽ tạo thông báo.
                    $arrUserId[] = $student->User->users_id; // sinh viên\
                    // nếu người sửa trnạg thái minh chứng là CVHT thì chỉ tạo 1 thông báo
                    if(Auth::user()->users_id != $student->Classes->Staff->User->users_id){
                        $arrUserId[] = Auth::user()->users_id;
                        $arrUserId[] = $student->Classes->Staff->User->users_id;
                    }else{
                        $arrUserId[] = Auth::user()->users_id;
                    }

                    $notifications->save();
                    $notifications->Users()->attach($arrUserId);
                }
            }
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
