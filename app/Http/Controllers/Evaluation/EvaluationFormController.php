<?php

namespace App\Http\Controllers\Evaluation;

use App\Model\EvaluationCriteria;
use App\Model\EvaluationForm;
use App\Http\Controllers\Controller;

use App\Model\Role;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = EvaluationCriteria::all();
        return view('evaluation-form.index',compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($semesterId)
    {
//        $user = Auth::user();
//
//        $form = new EvaluationForm();
//        $form->student_id = $user->Student->id;
//        $form->semester_id = $semesterId;
//        $form->save();
        return view('evaluation-form.index',compact('form','user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form = EvaluationForm::find(array('id' => $id));
        $evaluationCriterias = EvaluationCriteria::where('level',1)->get();
        $user = Auth::user();
//dd($form->Student);
        //lấy ra danh sách các role có thể chấm điểm để hiển thị các ô input
        $listRoleCanMark = Role::whereHas('permissions',function($query){
            $query->where('name', 'like', '%can-mark%');
        })->get();
        return view('evaluation-form.show',compact('form','user','evaluationCriterias','listRoleCanMark'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function handleDetail($str){
        $arrStr = explode(';',$str);
        $value = $title = "<tr>";

        foreach($arrStr as  $item){
            $arrValue = explode(':',$item);
            $title .= "<th>" . $arrValue[0] . "</th>";
            $value .= "<td>" . $arrValue[1] . "</td>";
        }
        $html = "<table border='0' style='width:-webkit-fill-available '> ". $title."</tr>" .$value ."</tr> </table>";
        return $html;
    }



}
