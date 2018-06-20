<?php

namespace App\Http\Controllers\Proof;

use App\Model\EvaluationCriteria;
use App\Model\EvaluationForm;
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
use Yajra\DataTables\DataTables;

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
//        $proofList = Proof::where('created_by',$userLogin->Student->id)->get();

        $evaluationCriterias = EvaluationCriteria::whereNotNull('proof')->get();

        // phải lấy các học kì có thời gian kết thúc chấm lớn hơn thời gian hiện tại.
        // tránh trường hợp sv thêm minh chứng vào các học kì trước.
        $semesters = Semester::where('date_end_to_mark','>=',Carbon::now()->format(DATE_FORMAT_DATABASE))->orderBy('id','DESC')->get();

        return view('proof.index', compact('proofList','evaluationCriterias','semesters','userLogin'));
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $proof = Proof::find($id);
        return response()->json([
            'proof' => $proof,
            'status' => true
        ],200);
    }

    public function update(Request $request, $id)
    {
        $arrRule = $arrMessage = array();
        if($request->evaluation_criteria != 0){
            $arrRule['evaluation_criteria'] = 'sometimes|exists:evaluation_criterias,id';
            $arrMessage['evaluation_criteria.exists'] = 'Tiêu chí không tồn tại';
        }

        if($request->semester != 0){
            $arrRule['semester'] = 'sometimes|exists:semesters,id';
            $arrMessage['semester.exists'] = 'Học kì không tồn tại';
        }

        $validator = Validator::make($request->all(), $arrRule,$arrMessage);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $proof = Proof::find($id);
            if (!empty($proof)) {
                if(!empty($request->semester_id)){
                    $proof->semester_id = $request->semester_id;
                }
                if(!empty($request->evaluation_criteria_id)){
                    $proof->evaluation_criteria_id  = $request->evaluation_criteria_id;
                }
                $proof->save();
                return response()->json([
                    'proof' => $proof,
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
        $arrRule = array(
            'fileUpload' => 'required'
        );
        $arrMessage = array(
            'fileUpload.required' => 'Bắt buộc chọn File'
        );

        if($request->evaluation_criteria != 0){
            $arrRule['evaluation_criteria'] = 'sometimes|exists:evaluation_criterias,id';
            $arrMessage['evaluation_criteria.exists'] = 'Tiêu chí không tồn tại';
        }

        if($request->semester != 0){
            $arrRule['semester'] = 'sometimes|exists:semesters,id';
            $arrMessage['semester.exists'] = 'Học kì không tồn tại';
        }

       $validator = Validator::make($request->all(), $arrRule,$arrMessage);

       if ($validator->fails()) {
           return response()->json([
               'status' => false,
               'arrMessages' => $validator->errors()
           ], 200);
       } else {
           $userLogin = $this->getUserLogin();
           $arrProof = array();
           foreach ($request->fileUpload as $proof) {
               $fileName = str_random(13) . "_" . $proof->getClientOriginalName();
               $fileName = $this->convert_vi_to_en(preg_replace('/\s+/', '', $fileName));
               while (file_exists(PROOF_PATH . $fileName)) {
                   $fileName = $this->convert_vi_to_en(str_random(13) . "_" . $fileName);
               }
               $proof->move( PROOF_PATH, $fileName);  // lưu file vào thư mục

               $proofTmp = [
                   'name' => $fileName,
                   'created_by' => $userLogin->Student->id,
               ];
               if(!empty($request->semester)){
                   $proofTmp['semester_id'] = $request->semester;
               }
               if(!empty($request->evaluation_criteria)){
                   $proofTmp['evaluation_criteria_id'] = $request->evaluation_criteria;
               }
               $arrProof[] = $proofTmp;
           }
           Proof::insert($arrProof);

           return response()->json([
               'status' => true
           ], 200);
       }
    }

    public function destroy($id)
    {
        $proof = Proof::find($id);
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
                    $title = "Sửa trạng thái minh chứng của sinh viên: ".$student->User->users_id."-".$student->User->name ." thuộc lớp: ".$student->Classes->name;
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

    public function checkFileUpload(Request $request)
    {
        $arrFile = $request->file('fileUpload');
        foreach ($arrFile as $file) {
            if (!in_array($file->getClientOriginalExtension(), FILE_VALID)) {
                $arrMessage = array("fileImport" => ["File " . $file->getClientOriginalName() . " không hợp lệ. File hợp lệ: img,jpg,pdf,png,jpeg,bmp "]);
                return response()->json([
                    'status' => false,
                    'arrMessages' => $arrMessage
                ], 200);
            }
        }
        return response()->json([
            'status' => true,
        ], 200);
    }

    public function ajaxGetProofs(Request $request){
        $userLogin = Auth::user();

        $proofs = DB::table('proofs')
            ->leftJoin('evaluation_criterias','evaluation_criterias.id','=','proofs.evaluation_criteria_id')
            ->leftJoin('students','students.id','=','proofs.created_by')
            ->leftJoin('semesters','semesters.id','=','proofs.semester_id')
            ->where('proofs.created_by', $userLogin->Student->id)
            ->select(
                'evaluation_criterias.content',
                'proofs.id as proofId',
                'proofs.name as proofName',
                'proofs.valid',
                'semesters.year_from',
                'semesters.year_to',
                DB::raw("CONCAT(semesters.year_from,'-',semesters.year_to) as semesterYear"),
                'semesters.term',
                'semesters.id as semesterId'
            );
        return DataTables::of($proofs)
            ->editColumn('valid', function ($proof){
                return ($proof->valid) ? "Hợp lệ" : "Không hợp lệ";
            })
            ->editColumn('content', function ($proof){
                return ($proof->content) ? $proof->content : "Chưa chọn tiêu chí";
            })
            ->addColumn('action', function ($proof) use ($userLogin) {
                $linkGetFile = route('evaluation-form-get-file',$proof->proofId); // dung chung
                $linkViewFile = "<a title='Xem minh chứng' style='color:white;' data-proof-id='$proof->proofId' id='proof-view-file' data-get-file-link='$linkGetFile' class='btn btn-primary'> 
                   <i class='fa fa-eye' aria-hidden='true'></i></a>";
                if(!empty($proof->semesterId)){
                    //nếu có học kì. kiểm tra nếu đang ở học kì hiện tại thì ở ở trong thời gian chấm.
                    // nếu ở trong học kì sau thì cho sửa thoải mái
                    $semester = Semester::find($proof->semesterId);
                    if($semester->id == $this->getCurrentSemester()->id) {
                        $markTimeOfUserLoginBySemester = $semester->MarkTimes()->where('role_id', $userLogin->Role->id)->first();
                        $marTimeStart = $markTimeOfUserLoginBySemester->mark_time_start;
                        $marTimeEnd = $markTimeOfUserLoginBySemester->mark_time_end;
                        if (self::checkTimeMark($marTimeStart, $marTimeEnd)) {
                            $linkUpdate = route('proof-update', $proof->proofId);
                            $linkEditFile = "<button title='Sửa minh chứng' style='color: white;' date-proof-id='$proof->proofId' data-get-file-link='$linkGetFile' id='proof-view-update-file' data-link-update-proof-file='$linkUpdate' class='btn btn-primary'> <i class='fa fa-edit' aria-hidden='true'></i></button>";

                            $linkDelete = route('proof-destroy', $proof->proofId);
                            $linkDeleteFile = "<button title='Xóa minh chứng' type='button' class='btn btn-danger' data-proof-id='$proof->proofId' id='proof-destroy' data-proof-destroy-link='$linkDelete'><i class='fa fa-trash'></i></button>";

                            return "<p class='bs-component'>$linkViewFile $linkEditFile $linkDeleteFile</p> ";
                        }
                    }else{
                        $linkUpdate = route('proof-update', $proof->proofId);
                        $linkEditFile = "<button title='Sửa minh chứng' style='color: white;' date-proof-id='$proof->proofId' data-get-file-link='$linkGetFile' id='proof-view-update-file' data-link-update-proof-file='$linkUpdate' class='btn btn-primary'> <i class='fa fa-edit' aria-hidden='true'></i></button>";

                        $linkDelete = route('proof-destroy', $proof->proofId);
                        $linkDeleteFile = "<button title='Xóa minh chứng' type='button' class='btn btn-danger' data-proof-id='$proof->proofId' id='proof-destroy' data-proof-destroy-link='$linkDelete'><i class='fa fa-trash'></i></button>";

                        return "<p class='bs-component'>$linkViewFile $linkEditFile $linkDeleteFile</p> ";
                    }

                }elseif (empty($proof->semesterId)){
                    $linkUpdate = route('proof-update',$proof->proofId);
                    $linkEditFile = "<button title='Sửa minh chứng' style='color: white;' date-proof-id='$proof->proofId' data-get-file-link='$linkGetFile' id='proof-view-update-file' data-link-update-proof-file='$linkUpdate' class='btn btn-primary'> <i class='fa fa-edit' aria-hidden='true'></i></button>";

                    $linkDelete = route('proof-destroy',$proof->proofId);
                    $linkDeleteFile = "<button title='Xóa minh chứng' type='button' class='btn btn-danger' data-proof-id='$proof->proofId' id='proof-destroy' data-proof-destroy-link='$linkDelete'><i class='fa fa-trash'></i></button>";
                    return "<p class='bs-component'>$linkViewFile $linkEditFile $linkDeleteFile</p> ";

                }


                return "<p class='bs-component'>$linkViewFile</p> ";

            })
            ->make(true);
    }
}
