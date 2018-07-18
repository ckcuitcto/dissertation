<?php

namespace App\Http\Controllers\Transcript;

use App\Model\AcademicTranscript;
use App\Model\EvaluationCriteria;
use App\Model\EvaluationForm;
use App\Model\EvaluationResult;
use App\Model\Faculty;
use App\Model\Role;
use App\Model\Semester;
use App\Model\Student;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Validator;
class TranscriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userLogin = $this->getUserLogin();

        $currentSemester = $this->getCurrentSemester();
        $semesters = Semester::select('id',DB::raw("CONCAT('Học kì: ',term,'*** Năm học: ',year_from,' - ',year_to) as value"))->get()->toArray();

        if($userLogin->Role->weight == ROLE_PHONGCONGTACSINHVIEN OR $userLogin->Role->weight == ROLE_ADMIN){
            $faculties = Faculty::all()->toArray();
            $faculties = array_prepend($faculties,array('id' => 0,'name' => 'Tất cả khoa'));
        }else{
            $faculties = Faculty::where('id',$userLogin->Faculty->id)->get()->toArray();
        }
        return view('transcript.index',compact('faculties','semesters','currentSemester'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::find($id);
        if (!empty($student)) {
            $user = User::where('users_id', $student->user_id)->first();

            $evaluationForms = EvaluationForm::where('student_id', $id)->get();

            $userLogin = $this->getUserLogin();

            foreach ($evaluationForms as $value) {
                $this->authorize($value, 'view');
            }
            $rolesCanMark = Role::whereHas('permissions', function ($query) {
                $query->where('name', 'like', '%can-mark%');
            })->select('id', 'name', 'display_name', 'weight')->orderBy('id')->get()->toArray();

            // danh  sách user chấm điểm + role + total
            $scoreList = DB::table('evaluation_results')
                ->leftJoin('evaluation_criterias', 'evaluation_criterias.id', '=', 'evaluation_results.evaluation_criteria_id')
                ->leftJoin('evaluation_forms', 'evaluation_forms.id', '=', 'evaluation_results.evaluation_form_id')
                ->leftJoin('users', 'users.users_id', '=', 'evaluation_results.marker_id')
                ->rightJoin('roles', 'roles.id', '=', 'users.role_id')
                ->select(
                    DB::raw('SUM(evaluation_results.marker_score) as totalRoleScore'),
                    'evaluation_forms.total',
                    'users.role_id',
                    'evaluation_results.marker_id',
                    'evaluation_forms.id as evaluationFormId',
                    'roles.*'
                )
                ->where([
                    ['evaluation_forms.student_id', $id],
                    ['evaluation_criterias.level', '=', '1'],
                ])
                ->groupBy('evaluation_results.marker_id', 'evaluation_forms.id')
                ->get();

            $arrRolesCanMarkWithScore = array();
            foreach ($evaluationForms as $evaluationform) {
                $scoreListByEvaluationForm = $scoreList->where('evaluationFormId', $evaluationform->id);

                $arrRolesCanMarkWithScore[$evaluationform->id] = array();
                foreach ($rolesCanMark as $value) {
                    if (!empty($scoreListByEvaluationForm->where('role_id', $value['id'])->first()->totalRoleScore)) {
                        $value['totalRoleScore'] = $scoreListByEvaluationForm->where('role_id', $value['id'])->first()->totalRoleScore;
                        $arrRolesCanMarkWithScore[$evaluationform->id][] = $value;
                    } else {
                        $value['totalRoleScore'] = 0;
                        $arrRolesCanMarkWithScore[$evaluationform->id][] = $value;
                    }
                }
            }

            $monitor = Student::leftJoin('users','students.user_id','=','users.users_id')
                ->leftJoin('roles','users.role_id','=','roles.id')
                ->where('class_id',$user->Student->class_id)
                ->where('roles.weight',ROLE_BANCANSULOP)
                ->first();

            return view('transcript.show', compact('user', 'userLogin', 'evaluationForms', 'arrRolesCanMarkWithScore', 'rolesCanMark','monitor'));
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // lấy các tiêu chí con
    private function getEvaluationCriteriaByParentId($parentId)
    {
        $arrChildId = array();
        $evaluationCriteria = EvaluationCriteria::find($parentId);
        foreach ($evaluationCriteria->Child as $key => $childLevel1) {
            $arrChildId[] = $childLevel1->id;
            if (!empty($childLevel1->Child)) {
                foreach ($childLevel1->Child as $key => $childLevel2) {
                    $arrChildId[] = $childLevel2->id;
                }
            }
        }
        return $arrChildId;
    }

    //  lấy điểm của mỗi  nội dung đánh giá level 1 trong 1 form
    private function getTotalScoreTopic($evaluationFormId, $markerId, $evaluationCriteriaId)
    {
        $arrEvaluationCriterial = $this->getEvaluationCriteriaByParentId($evaluationCriteriaId);
//        $evaluationResults = EvaluationResult::where('evaluation_form_id',$id)->get();
        $score = DB::table('evaluation_results')->where([
            'evaluation_form_id' => $evaluationFormId,
            'marker_id' => $markerId
        ])->whereIn('evaluation_criteria_id', $arrEvaluationCriterial)->select(DB::raw('sum(marker_score) as total'))->first();
        return $score->total;
    }

    // lấy điểm đánh giá  một form của 1 người chấm
    public function geTotalScoreForm($evaluationFormId, $markerId)
    {
        $total = 0;
        $evaluationCriteria = EvaluationCriteria::where('level', 1)->get();
        foreach ($evaluationCriteria as $key => $value) {
            $score = $this->getTotalScoreTopic($evaluationFormId, $markerId, $value->id);
            if ($score > $value->mark_range_to) {
                $score = $value->mark_range_to;
            }
            $total += $score;
        }
        return $total;
    }

    public function ajaxGetUsers(Request $request)
    {
        $user = $this->getUserLogin();
        $students = $this->getStudentByRoleUserLogin($user);
        $dataTables = DataTables::of($students)
            ->addColumn('action', function ($student) use ($user) {
//                if($student->totalScore != 0){
                // nếu làm v. khi chấm điểm r. cố vấn hcấm lại = 0 điểm thì sẽ bị lỗi( k hiện điểm chấm)
                //nên phải set lại = status
                if($student->evaluationFormStatus != 0){
                    $linkView = '<a title="Xem phiếu điểm" target="_blank" href="' . route('evaluation-form-show', $student->evaluationFormId) . '" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>';
                }else{
                    $linkView = '<a title="Chưa chấm" target="_blank" href="' . route('evaluation-form-show', $student->evaluationFormId) . '" class="btn btn-xs btn-danger"><i class="fa fa-eye"></i></a>';
                }

                //nếu ng đang đăng nhập đã chấm thì hiện ra điểm
                if(!empty($student->evaluationFormStatus)){
                    $currentTotal = '<button title="Điểm hiện tại" class="btn btn-outline-success"><i class="fa fa-check"></i>' . $student->totalScore . ' đ</button>';
                    $linkView = $linkView.' '.$currentTotal;
                }

                $result = DB::table('evaluation_forms')
                    ->leftJoin('evaluation_results', 'evaluation_forms.id', '=', 'evaluation_results.evaluation_form_id')
                    ->leftJoin('evaluation_criterias', 'evaluation_criterias.id', '=', 'evaluation_results.evaluation_criteria_id')
                    ->where([
//                        ['evaluation_forms.semester_id', $student->semesterId],
                        ['evaluation_forms.student_id', $student->studentId],
                        ['evaluation_results.marker_id', $user->users_id],
                        ['evaluation_criterias.level', 1],
                    ])
                    ->select(
                        DB::raw('SUM(evaluation_results.marker_score) as score'),
                        'evaluation_forms.status'
                    )->first();
                if ($result->score OR $result->status != 0) {
                    $score = $result->score OR 0;
                    // nếu điểm sinh viên = 0 => chưa chấm => hiện màu đỏ
                        $iconMarked = '<button title="Điểm bạn chấm" class="btn btn-success"><i class="fa fa-check"></i>' . $score . ' đ</button>';
//                    $linkView = $linkView . ' ' . $result->score;
                    $linkView = $linkView . ' ' . $iconMarked;
                }
                return $linkView;
            })
            ->filter(function ($student) use ($request) {
                $faculty = $request->has('faculty_id');
                $facultyValue = $request->get('faculty_id');

                if (!empty($faculty) AND $facultyValue != 0) {
                    $student->where('users.faculty_id', '=', $facultyValue);

                    $class = $request->has('class_id');
                    $classValue = $request->get('class_id');
                    if (!empty($class) AND $classValue != 0) {
                        $student->where('students.class_id','=', $classValue);
                    }
                }

                $semester = $request->has('semester_id');
                $semesterValue = $request->get('semester_id');
                if (!empty($semester) AND $semesterValue != 0) {
                    $student->where('student_list_each_semesters.semester_id', '=', $semesterValue);
                    $student->where('evaluation_forms.semester_id', '=', $semesterValue);
                }
            });


        return $dataTables->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function academicTranscript()
    {
        $userLogin = $this->getUserLogin();

        $currentSemester = $this->getCurrentSemester();
        $semesters = Semester::select('id',DB::raw("CONCAT('Học kì: ',term,'*** Năm học: ',year_from,' - ',year_to) as value"))->get()->toArray();
        $semestersNoAll = $semesters;
        $semesters = array_prepend($semesters,array('id' => 0,'value' => 'Tất cả học kì'));

        if($userLogin->Role->weight == ROLE_PHONGCONGTACSINHVIEN OR $userLogin->Role->weight == ROLE_ADMIN){
            $faculties = Faculty::all()->toArray();
            $faculties = array_prepend($faculties,array('id' => 0,'name' => 'Tất cả khoa'));

            $facultiesNoAll = $faculties;
            unset($facultiesNoAll[0]);
        }else{
            $faculties = Faculty::where('id',$userLogin->Faculty->id)->get()->toArray();
        }

        $strEvaluationCriteriaChildParent1 = implode(',',EVALUATION_CRITERIAS_CHILD_PARENT_1);
        $evaluationCriterias = DB::select("select * from evaluation_criterias where ( level = ? OR id in ($strEvaluationCriteriaChildParent1)) AND id <> ? order by level DESC", [1,YTHUCTHAMGIAHOCTAP_ID]);

        return view('transcript.academic-transcript',compact('faculties','facultiesNoAll','semesters','semestersNoAll','currentSemester','evaluationCriterias'));
    }

    public function ajaxGetAcademicTranscript(Request $request)
    {
        $user = $this->getUserLogin();
        $academicTranscript = $this->getAcademicTranscriptByRoleUserLogin($user);
        $dataTables = DataTables::of($academicTranscript)
            ->editColumn('totalScore', function ($aca){
                if($aca->totalScore > 100){
                    $aca->totalScore = 100;
                }
                return $aca->totalScore;
            })
            ->addColumn('action', function ($aca) use ($user) {
                $linkEdit = route('edit-academic-transcript', $aca->academicTranscriptId);
                $linkUpdate = route('add-academic-transcript');

                $btnEdit = "<a style='color: white' title='Sửa điểm' data-id='$aca->academicTranscriptId' data-edit-link='$linkEdit'
                data-update-link='$linkUpdate' class='btn btn-primary update-academic-transcript'> <i class='fa fa-edit' aria-hidden='true'></i> </a>";

                return "<span class='bs-component'>$btnEdit</span> ";
            })
            ->filter(function ($student) use ($request) {
                $faculty = $request->has('faculty_id');
                $facultyValue = $request->get('faculty_id');

                if (!empty($faculty) AND $facultyValue != 0) {
                    $student->where('users.faculty_id', '=', $facultyValue);

                    $class = $request->has('class_id');
                    $classValue = $request->get('class_id');
                    if (!empty($class) AND $classValue != 0) {
                        $student->where('students.class_id','=', $classValue);
                    }
                }

                $semester = $request->has('semester_id');
                $semesterValue = $request->get('semester_id');
                if (!empty($semester) AND $semesterValue != 0) {
                    $student->where('academic_transcripts.semester_id', '=', $semesterValue);
                    $student->where('academic_transcripts.semester_id', '=', $semesterValue);
                }
            });

        return $dataTables->make(true);
    }

    private function getAcademicTranscriptByRoleUserLogin(User $user)
    {
        $arrUserId = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.weight', '<=', ROLE_BANCANSULOP)
            ->select('users.users_id')->get()->toArray();
        foreach ($arrUserId as $key => $value) {
            $userIds[$key] = [$value->users_id];
        }
        if (!empty($userIds)) {
            $students = DB::table('academic_transcripts')
                ->leftJoin('classes', 'classes.id', '=', 'academic_transcripts.class_id')
                ->leftJoin('students', 'students.user_id', '=', 'academic_transcripts.user_id')
                ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
                ->leftJoin('faculties', 'faculties.id', '=', 'users.faculty_id')
                ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                ->whereIn('users.users_id', $userIds)
                ->select(
                    'academic_transcripts.id as academicTranscriptId',
                    'users.users_id',
                    'users.name as userName',
                    'roles.display_name',
                    'classes.name as className',
                    'faculties.name as facultyName',
                    'students.academic_year_from',
                    'students.academic_year_to',
                    DB::raw("CONCAT(students.academic_year_from,'-',students.academic_year_to) as academic"),
                    'students.id',
                    'users.status',
                    'students.id as studentId',
                    'users.faculty_id',
                    'students.class_id',
                    'roles.id as role_id',
                    'academic_transcripts.semester_id as semesterId',
                    'academic_transcripts.score_ia',
                    'academic_transcripts.score_ib',
                    'academic_transcripts.score_ic',
                    'academic_transcripts.score_i',
                    'academic_transcripts.score_ii',
                    'academic_transcripts.score_iii',
                    'academic_transcripts.score_iv',
                    'academic_transcripts.score_v',
                    DB::raw("
                    academic_transcripts.score_i +
                    academic_transcripts.score_ii +
                    academic_transcripts.score_iii +
                    academic_transcripts.score_iv +
                    academic_transcripts.score_v
                    as totalScore")
                )->distinct();
            return $students;
        }
        return false;
    }

    public function addAcademicTranscript(Request $request)
    {

        $strEvaluationCriteriaChildParent1 = implode(',',EVALUATION_CRITERIAS_CHILD_PARENT_1);
        $evaluationCriterias = DB::select("select * from evaluation_criterias where ( level = ? OR id in ($strEvaluationCriteriaChildParent1)) AND id <> ? order by level DESC", [1,YTHUCTHAMGIAHOCTAP_ID]);

        $arrValidatorRole = array();
        $arrValidatorRoleMessage = array();
        foreach ($evaluationCriterias as $value) {

            $arrValidatorRole["evaluation_criteria_".$value->id] = "required|min:$value->mark_range_from|max:$value->mark_range_to|numeric";
            $arrValidatorRoleMessage["evaluation_criteria_".$value->id . ".required"] = 'Bắt buộc nhập điểm';
            $arrValidatorRoleMessage["evaluation_criteria_".$value->id . ".min"] = "Điểm phải lớn hơn $value->mark_range_from";
            $arrValidatorRoleMessage["evaluation_criteria_".$value->id . ".max"] = "Điểm phải nhỏ hơn $value->mark_range_to";
            $arrValidatorRoleMessage["evaluation_criteria_".$value->id . ".numeric"] = 'Điểm phải là số';
        }
//        var_dump($arrValidatorRoleMessage);
//        dd($arrValidatorRole);
//        $arrValidatorRole['add_faculty_id'] = "required|exists:faculties,id";
        $arrValidatorRole['add_class_id'] = "required|exists:classes,id";
        $arrValidatorRole['add_student_id'] = "required|exists:students,user_id";
        $arrValidatorRole['add_semester_id'] = "required|exists:semesters,id";

//        $arrValidatorRoleMessage['add_faculty_id.required'] = "Bắt buộc chọn khoa";
//        $arrValidatorRoleMessage['add_faculty_id.exists'] = "Khoa không tồn tại";

        $arrValidatorRoleMessage['add_class_id.required'] = "Bắt buộc chọn lớp";
        $arrValidatorRoleMessage['add_class_id.exists'] = "Lớp không tồn tại";

        $arrValidatorRoleMessage['add_student_id.required'] = "Bắt buộc chọn sinh viên";
        $arrValidatorRoleMessage['add_student_id.exists'] = "Sinh viên không tồn tại";

        $arrValidatorRoleMessage['add_student_id.required'] = "Bắt buộc chọn học kì";
        $arrValidatorRoleMessage['add_student_id.exists'] = "Học kì không tồn tại";

        $validator = Validator::make($request->all(), $arrValidatorRole, $arrValidatorRoleMessage);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $arrValue = array();
            foreach ($evaluationCriterias as $value) {
                // lúc này name của input đang là 1 chuỗi evaluation_criteria_id
                // nên phải cắt ra lấy id
                $evaluationCriteriaId = "evaluation_criteria_".$value->id;
                $key = ARRAY_EVALUATION_CRITERIA_VS_ACADEMIC_TRANSCRIPT[$value->id];
                $arrValue[$key] = $request->$evaluationCriteriaId;
            }
            $arrValue['note'] = $request->note;
            AcademicTranscript::updateOrCreate(
                [
                    'user_id' => $request->add_student_id,
                    'semester_id' => $request->add_semester_id,
                    'class_id' => $request->add_class_id,
                ], $arrValue
            );

            return response()->json([
                'status' => true
            ], 200);
        }
    }

    public function editAcademicTranscript($id)
    {
        $academicTranscript = DB::table('academic_transcripts')
            ->leftJoin('classes', 'classes.id', '=', 'academic_transcripts.class_id')
            ->leftJoin('students', 'students.user_id', '=', 'academic_transcripts.user_id')
            ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
            ->leftJoin('faculties', 'faculties.id', '=', 'users.faculty_id')
            ->where('academic_transcripts.id', $id)
            ->select(
                'academic_transcripts.id',
                'users.faculty_id as add_faculty_id',
                'faculties.name as add_faculty_name',
                'students.class_id as add_class_id',
                'classes.name as add_class_name',
                'students.user_id as add_student_id',
                'users.name as add_student_name',
                'academic_transcripts.semester_id as add_semester_id',
                'academic_transcripts.score_ia',
                'academic_transcripts.score_ib',
                'academic_transcripts.score_ic',
                'academic_transcripts.score_ii',
                'academic_transcripts.score_iii',
                'academic_transcripts.score_iv',
                'academic_transcripts.score_v',
                'academic_transcripts.note'
            )->first();
        $arrAcademicTranscript = (array)$academicTranscript;
        foreach(ARRAY_ACADEMIC_TRANSCRIPT_TO_EVALUATION_CRITERIA as $key => $value){
            $arrAcademicTranscript["evaluation_criteria_$value"] = $arrAcademicTranscript['score_'.$key];
            unset($arrAcademicTranscript['score_'.$key]);
        }
        if(!empty($academicTranscript)){
            return response()->json([
                'academicTranscript' => $arrAcademicTranscript,
                'status' => true
            ], 200);
        }else{
            return response()->json([
                'status' => false
            ], 200);
        }

    }
}
