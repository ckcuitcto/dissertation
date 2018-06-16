<?php

namespace App\Http\Controllers\Transcript;

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
            return view('transcript.show', compact('user', 'userLogin', 'evaluationForms', 'arrRolesCanMarkWithScore', 'rolesCanMark'));
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
                $linkView = '<a title="View" href="' . route('transcript-show', $student->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>';
                $result = DB::table('evaluation_forms')
                    ->leftJoin('evaluation_results', 'evaluation_forms.id', '=', 'evaluation_results.evaluation_form_id')
                    ->leftJoin('evaluation_criterias', 'evaluation_criterias.id', '=', 'evaluation_results.evaluation_criteria_id')
                    ->where([
//                        ['evaluation_forms.semester_id', $student->semesterId],
                        ['evaluation_forms.student_id', $student->studentId],
                        ['evaluation_results.marker_id', $user->users_id],
                        ['evaluation_criterias.level', 1],
                    ])
                    ->select(DB::raw('SUM(evaluation_results.marker_score) as score'))->first();
                if ($result->score) {
                    $score = $result->score;
                    $iconMarked = '<button class="btn btn-success"><i class="fa fa-check"></i>' . $score . ' đ</button>';
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

//        if ($keyword = $request->get('search')['value']) {
            // override users.name global search
//            $dataTables->filterColumn('users.faculty_id', 'where', '=', "%$keyword%");

            // override users.id global search - demo for concat
//            $dataTables->filterColumn('students.class_id', 'where', "=", "%$keyword%");
//        }
        return $dataTables->make(true);
    }
}
