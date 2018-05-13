<?php

namespace App\Http\Controllers\Transcript;

use App\Model\EvaluationForm;
use App\Model\Role;
use App\Model\Semester;
use App\Model\Student;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TranscriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $students = $this->getStudentByRoleUserLogin($user);

        return view('transcript.index', compact('students'));
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
        $user = User::find($student->user_id);

        $rolesCanMark = Role::whereHas('permissions', function ($query) {
            $query->where('name', 'like', '%can-mark%');
        })->select('id','name','display_name')->orderBy('id')->get()->toArray();


        // danh  sách user chấm điểm + role + total
        $scoreList = DB::table('evaluation_results')
            ->leftJoin('evaluation_criterias', 'evaluation_criterias.id', '=', 'evaluation_results.evaluation_criteria_id')
            ->leftJoin('evaluation_forms', 'evaluation_forms.id', '=', 'evaluation_results.evaluation_form_id')
            ->leftJoin('users', 'users.id', '=', 'evaluation_results.marker_id')
            ->select(DB::raw('SUM(evaluation_results.marker_score) as totalRoleScore'), 'evaluation_forms.total','users.role_id','evaluation_results.marker_id','evaluation_forms.id as evaluationFormId')
            ->where([
                ['evaluation_forms.student_id', $id],
                ['evaluation_criterias.level','>','1']
            ])
            ->groupBy('evaluation_results.marker_id','evaluation_forms.id')
            ->get();
//        var_dump($scoreList->where('evaluationFormId',9));
//        dd($scoreList);

        $evaluationForms = EvaluationForm::where('student_id', $id)->get();
        return view('transcript.show', compact('user', 'evaluationForms','rolesCanMark','scoreList'));
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
}
