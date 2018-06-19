<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Model\EvaluationForm;
use App\Model\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\News;
use App\Model\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
        if($userLogin->Role->weight >= ROLE_PHONGCONGTACSINHVIEN ){
            $newsList = News::all();
        }else{
            $newsList = News::where( 'faculty_id','=',$userLogin->faculty_id)->orWhere('faculty_id','=',null)->orderBy('id','DESC')->limit(6)->get();
        }

        $timeList = $this->getCurrentSemester();
        if(!empty($userLogin->Student)) {

            $evaluationForm = EvaluationForm::where('student_id', $userLogin->Student->id)
                ->where('semester_id',$timeList->id)
                ->first();

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
                    ['evaluation_forms.student_id', $userLogin->Student->id],
                    ['evaluation_criterias.level', '=', '1'],
                    ['evaluation_forms.semester_id',$timeList->id]
                ])
                ->groupBy('evaluation_results.marker_id', 'evaluation_forms.id')
                ->get();

            $arrRolesCanMarkWithScore = array();
            foreach ($rolesCanMark as $value) {
                if (!empty($scoreList->where('role_id', $value['id'])->first()->totalRoleScore)) {
                    $value['totalRoleScore'] = $scoreList->where('role_id', $value['id'])->first()->totalRoleScore;
                    $arrRolesCanMarkWithScore[] = $value;
                } else {
                    $value['totalRoleScore'] = 0;
                    $arrRolesCanMarkWithScore[] = $value;
                }
            }

            return view('home.home', compact('newsList', 'timeList', 'evaluationForm', 'rolesCanMark', 'arrRolesCanMarkWithScore'));
        }else{
            return view('home.home', compact('newsList','timeList'));
        }
    }

    public function showLoginAnimatedForm()
    {
        return view('auth.login-animated');
    }

}
