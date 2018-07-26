<?php

namespace App\Http\Controllers\Proof;

use App\Model\EvaluationCriteria;
use App\Model\EvaluationForm;
use App\Model\Faculty;
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
        $userLogin = $this->getUserLogin(); // lấy ra người dùng đang đăng nhập

        // nếu role vào k phải là học sinh, ban cán sự
        // thì chuyển sang trang k có quyền
        if($userLogin->Role->weight >= ROLE_COVANHOCTAP) {
            return view('errors.403');
        }

        $evaluationCriterias = EvaluationCriteria::whereNotNull('proof')->get();

        // phải lấy các học kì có thời gian kết thúc chấm lớn hơn thời gian hiện tại. ( là chưa kết thúc chấm)
        // tránh trường hợp sv thêm minh chứng vào các học kì trước.
        $semesters = Semester::where('date_end_to_mark','>=',Carbon::now()->format(DATE_FORMAT_DATABASE))->orderBy('id','DESC')->get();

        return view('proof.index', compact('proofList','evaluationCriterias','semesters','userLogin'));
    }

    public function list()
    {
        $userLogin = $this->getUserLogin(); // lấy ra người dùng đang đăng nhập

        // nếu role vào k phải là học sinh, ban cán sự
        // thì chuyển sang trang k có quyền
        if($userLogin->Role->weight < ROLE_COVANHOCTAP) {
            return view('errors.403');
        }

//        $evaluationCriterias = EvaluationCriteria::whereNotNull('proof')->get();
        // phải lấy các học kì có thời gian kết thúc chấm lớn hơn thời gian hiện tại. ( là chưa kết thúc chấm)
        // tránh trường hợp sv thêm minh chứng vào các học kì trước.
//        $semesters = Semester::where('date_end_to_mark','>=',Carbon::now()->format(DATE_FORMAT_DATABASE))->orderBy('id','DESC')->get();

        $userLogin = $this->getUserLogin();

        $currentSemester = $this->getCurrentSemester();
//        $semesters = Semester::select('id',DB::raw("CONCAT('Học kì: ',term,'*** Năm học: ',year_from,' - ',year_to) as value"))->get()->toArray();

        $semesters = Semester::select('id',DB::raw("CONCAT('Học kì: ',term,'*** Năm học: ',year_from,' - ',year_to) as value"))->get()->toArray();
        $semestersNoAll = $semesters;
        $semesters = array_prepend($semesters,array('id' => 0,'value' => 'Tất cả học kì'));

        if($userLogin->Role->weight == ROLE_PHONGCONGTACSINHVIEN OR $userLogin->Role->weight == ROLE_ADMIN){
            $faculties = Faculty::all()->toArray();
            $faculties = array_prepend($faculties,array('id' => 0,'name' => 'Tất cả khoa'));
        }else{
            $faculties = Faculty::where('id',$userLogin->Faculty->id)->get()->toArray();
        }

        return view('proof.list', compact('proofList','semesters','userLogin','faculties','currentSemester'));
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

                $proof->semester_id = ($request->semester_id) ? $request->semester_id : null;
                $proof->evaluation_criteria_id  =  ($request->evaluation_criteria_id) ? $request->evaluation_criteria_id : null;

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

        // nếu != 0 nghĩa là có chọn tiêu chí. thì phải kiểm tra xem tiêu chí có trong db không
        if($request->evaluation_criteria != 0){
            $arrRule['evaluation_criteria'] = 'sometimes|exists:evaluation_criterias,id';
            $arrMessage['evaluation_criteria.exists'] = 'Tiêu chí không tồn tại';
        }

        // nếu != 0 nghĩa là có chọn học kì. thì phải kiểm tra xem học kì có trong db không
        if($request->semester != 0){
            $arrRule['semester'] = 'sometimes|exists:semesters,id';
            $arrMessage['semester.exists'] = 'Học kì không tồn tại';
        }


        // đưa dữ liệu nhập, và các điều kiện vào để kiểm tra
        // $request->all() là để lấy ra tất cả các dữ liệu đã nhập ở form
       $validator = Validator::make($request->all(), $arrRule,$arrMessage);

        //kiểm tra nếu dữ liệu nhập vào và điều kiện fail
       if ($validator->fails()) {
           return response()->json([
               'status' => false,
               'arrMessages' => $validator->errors() // lấy ra nội dung bị sai
           ], 200);
       } else {
           $userLogin = $this->getUserLogin(); // lấy ra thông tin người dùng ĐANG ĐĂNG NHẬP
           $arrProof = array();

           // chạy mảng các file minh chứng đã nhập
           // mỗi $proof là tương ứng với 1 file (1 minh chứng)
           foreach ($request->fileUpload as $proof) {

               // bắt đầu đoạn code này dùng để LƯU FILE VÀO thư mục
               $fileName = str_random(13) . "_" . $proof->getClientOriginalName();
               $fileName = $this->convert_vi_to_en(preg_replace('/\s+/', '', $fileName));
               while (file_exists(PROOF_PATH . $fileName)) {
                   $fileName = $this->convert_vi_to_en(str_random(13) . "_" . $fileName); // tên của file.
               }
               $proof->move( PROOF_PATH, $fileName);  // lưu file vào thư mục
               // kết thúc đoạn code này dùng để LƯU FILE VÀO thư mục


               $proofTmp = [
                   'name' => $fileName,
                   'created_by' => $userLogin->Student->id,
               ];

               // kiểm tra nếu học kì k rỗng. thì sẽ lưu lại id
               if(!empty($request->semester)){
                   // tại sao 1 cái là semester và 1 cái là semester_id .
                   // cái semester là name của thẻ bên view. chỉ là tên, nhưng giá trị của nó vẫn là id của học kì
                   // semester_id là tên của cột lưu lại id của học kì trong bảng proof( minh chứng)
                   $proofTmp['semester_id'] = $request->semester;
               }

               // kiểm tra nếu tiêu chí k rỗng. thì sẽ lưu lại id
               if(!empty($request->evaluation_criteria)){
                   // tại sao 1 cái là evaluation_criteria và 1 cái là evaluation_criteria_id .
                   // cái evaluation_criteria là name của thẻ bên view. chỉ là tên, nhưng giá trị của nó vẫn là id của tiêu chí đánh giá
                   // evaluation_criteria_id là tên của cột lưu lại id của tiêu chí trong bảng proof( minh chứng)
                   $proofTmp['evaluation_criteria_id'] = $request->evaluation_criteria;
               }
               // thêm vào 1 mảng . để thêm 1 lần cho tối ưu code
               $arrProof[] = $proofTmp;
           }
           // thêm vào database
           Proof::insert($arrProof);

           // trả về true => thêm thành công
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

                    // nếu tiêu chí bị rỗng. ( chưa chọn tiêu chí) thì sẽ xuất nội dung khác
                    if(empty($evaluationCriterias)){
                        $noidungTieuChi = '';
                    }else {
                        $noidungTieuChi = " của tiêu chí $evaluationCriterias->content";
                    }
                    if(empty($semester)){
                        $noiDungHocKy = '';
                    }else {
                        $noiDungHocKy = " <b>Học kì $semester->term Năm học $semester->year_from - $semester->year_to.</b> ";
                    }
                    $from = PROOF_VALID[$proof->valid] ;
                    $to = PROOF_VALID[$request->valid];
                    if($request->valid != 0){
                        $notifications->content = $title. " từ $from thành $to $noidungTieuChi.<br>
                         Cố vấn học tập vui lòng vào kiểm tra lại file minh chứng của sinh viên và chỉnh sửa điểm phù hợp!<br>
                         $noiDungHocKy <br>
                         ";
                    }else{
                        $notifications->content = $title. " từ $from thành $to $noidungTieuChi .Với nội dung:<b>$request->note.</b> 
                        Cố vấn học tập vui lòng vào kiểm tra lại file minh chứng của sinh viên và chỉnh sửa điểm phù hợp!<br>
                         $noiDungHocKy <br>
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

    public function ajaxGetProofsOfStudent(Request $request){

        $userLogin = $this->getUserLogin();

        $options['all'] = true;
        $options['only-get-id']= true;
        $userIds = $this->getUserIdByUserLogin($userLogin);

        $proofs = DB::table('proofs')
            ->leftJoin('evaluation_criterias','evaluation_criterias.id','=','proofs.evaluation_criteria_id')
            ->leftJoin('students','students.id','=','proofs.created_by')
            ->leftJoin('users','students.user_id','=','users.users_id')
            ->leftJoin('classes','classes.id','=','students.class_id')
            ->leftJoin('semesters','semesters.id','=','proofs.semester_id')
//            ->where('proofs.created_by', $userLogin->Student->id)
            ->whereIn('students.user_id', $userIds)
            ->select(
                'evaluation_criterias.content',
                'proofs.id as proofId',
                'proofs.name as proofName',
                'proofs.valid',
                'semesters.year_from',
                'semesters.year_to',
                DB::raw("CONCAT(semesters.year_from,'-',semesters.year_to) as semesterYear"),
                'semesters.term',
                'semesters.id as semesterId',
                'semesters.date_end_to_re_mark',
                'users.name as userName',
                'classes.name as className',
                'students.user_id as userId'
//                'users.faculty_id',
//                'students.class_id',
//                'proofs.semester_id'
            )->orderBy('students.user_id','DESC')->orderBy('proofs.id','DESC');
        $dataTable = DataTables::of($proofs)
            ->editColumn('valid', function ($proof){
                return ($proof->valid) ? "Hợp lệ" : "Không hợp lệ";
            })
            ->editColumn('term', function ($proof){
                return ($proof->term) ? $proof->term : "";
            })
            ->editColumn('content', function ($proof){
                return ($proof->content) ? $proof->content : "Chưa chọn tiêu chí";
            })
            ->addColumn('action', function ($proof) use ($userLogin) {

                // lấy ra học kì của minh chứng
                // sau đó tìm thời gina chấm
                // nếu hiện tại <= thời gian kết thúc chấm phúc khảo => vẫn cho sửa

                if( strtotime($proof->date_end_to_re_mark) < strtotime(date('Y-m-d')))
                {
                    $canEdit = 2; // k thể edit
                }else{
                    $canEdit = 1; // có thể edit
                }

                $linkGetFile = route('evaluation-form-get-file',$proof->proofId); // dung chung
                $linkFilePath =  asset('upload/proof/'.$proof->proofName);
                $linkUpdate = route('update-valid-proof-file',$proof->proofId );

                $class = ($proof->valid == 0) ? "btn-danger" : "btn-primary";
                $linkViewFile =
                    "<a title='Xem minh chứng' data-can-edit='$canEdit' style='color:white;' data-proof-file-path='$linkFilePath'  data-link-update-proof-file='$linkUpdate'
                        data-proof-id='$proof->proofId' data-get-file-link='$linkGetFile' class='btn $class proof-view-file'>
                   <i class='fa fa-eye' aria-hidden='true'></i></a>";
                return $linkViewFile;
            })
            ->filter(function ($proof) use ($request) {
                $faculty = $request->has('faculty_id');
                $facultyValue = $request->get('faculty_id');

                if (!empty($faculty) AND $facultyValue != 0) {
                    $proof->where('users.faculty_id', '=', $facultyValue);

                    $class = $request->has('class_id');
                    $classValue = $request->get('class_id');
                    if (!empty($class) AND $classValue != 0) {
                        $proof->where('students.class_id','=', $classValue);
                    }
                }

                $semester = $request->has('semester_id');
                $semesterValue = $request->get('semester_id');
                if (!empty($semester) AND $semesterValue != 0) {
                    $proof->where('proofs.semester_id','=', $semesterValue);
                }
            });

        return $dataTable->make(true);
    }

    private function getUserIdByUserLogin($user){
//        $user = $this->getUserLogin();
        if ($user->Role->weight >= ROLE_PHONGCONGTACSINHVIEN) // admin va phong ctsv thì lấy tất cả user
        {
            $arrUserId = DB::table('users')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                ->select('users.users_id')->get()->toArray();
        } elseif ($user->Role->weight >= ROLE_BANCHUNHIEMKHOA) {
            // neeus laf ban chu nhiem khoa thi lay cung khoa
            $arrUserId = DB::table('users')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->where('users.faculty_id', $user->faculty_id)
                ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                ->select('users.users_id')->get()->toArray();
        } elseif ($user->Role->weight >= ROLE_COVANHOCTAP) {
            // neeus laf ban co van hoc tap thi lay cac sinh vien thuoc cac lop ma ng nay lam co  van
            // lấy danh sách các lớp mà ng này làm cố vấn
            $arrClassId = [];
            foreach ($user->Staff->Classes as $class) {
                $arrClassId[] = $class->id;
            }
            $arrUserId = DB::table('users')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('students', 'students.user_id', '=', 'users.users_id')
                ->where('users.faculty_id', $user->faculty_id)
                ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                ->whereIn('students.class_id', $arrClassId)
                ->select('users.users_id')->get()->toArray();
        } elseif ($user->Role->weight >= ROLE_BANCANSULOP) //ban can su lop, thì lấy user thuộc lop
        {
            $arrUserId = DB::table('users')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('students', 'students.user_id', '=', 'users.users_id')
                ->where('users.faculty_id', $user->faculty_id)
                ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                ->where('students.class_id', $user->Student->class_id)
                ->select('users.users_id')->get()->toArray();
        }
        foreach ($arrUserId as $key => $value){
            $userIds[$key] = [$value->users_id];
        }

        return $userIds;
    }
}
