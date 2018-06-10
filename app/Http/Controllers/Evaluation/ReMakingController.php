<?php

namespace App\Http\Controllers\Evaluation;

use App\Model\EvaluationForm;
use App\Model\Remaking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ReMakingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = null;
        if($request->user_id){
            $userId = $request->user_id;
        }
        return view('remaking.index',compact('remakings','userId'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'remarking_reason' => 'required'
        ], [
            'remarking_reason.required' => 'Lí do bắt buộc nhập'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        }

        $evaluatinForm = EvaluationForm::find($request->formId);
        if (!empty($evaluatinForm)) {

            // trạng thía mặc định là đang xử lí
            $evaluatinForm->Remaking()->create([
                'remarking_reason' => $request->remarking_reason,
                'status' => HANDLE,
            ]);
            // lưu thời gian chấm
            return response()->json([
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $remaking = Remaking::find($id);
        if(empty($remaking)){
            return response()->json([
                'status' => false
            ],200);
        }
        return response()->json([
            'remaking' => $remaking,
            'status' => true
        ],200);
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
        $validator = Validator::make($request->all(), [
            'remarking_reply' => 'sometimes|required',
            'remarking_reason' => 'sometimes|required',
            'status' => 'sometimes|required'
        ], [
            'remarking_reply.required' => 'Trả lời bắt buộc nhập',
            'remarking_reason.required' => 'Trả lời bắt buộc nhập',
            'status.required' => 'Trả lời bắt buộc nhập'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        }

        $remaking = Remaking::find($id);
        if (!empty($remaking)) {
            // trạng thía mặc định là đang xử lí
            if(!empty($request->remarking_reply)){
                $remaking->remarking_reply = $request->remarking_reply;
            }
            if(!empty($request->remarking_reason)){
                $remaking->remarking_reason = $request->remarking_reason;
            }
            if(!empty($request->status)){
                $remaking->status = $request->status;
            }
            $remaking->save();
            // lưu thời gian chấm
            return response()->json([
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }

//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($id)
//    {
//        //
//    }

    public function ajaxGetRemakings(Request $request){
        $userLogin = Auth::user();

        $options['all'] = true;
        $options['only-get-id']= true;
        $userIds = $this->getStudentByRoleUserLoginNotUseDataTable($userLogin,FALSE,$options);

        $remakings = DB::table('remakings')
            ->leftJoin('evaluation_forms','remakings.evaluation_form_id','=','evaluation_forms.id')
            ->leftJoin('students','students.id','=','evaluation_forms.student_id')
            ->leftJoin('users','users.users_id','=','students.user_id')
            ->leftJoin('classes','classes.id','=','students.class_id')
            ->leftJoin('semesters','semesters.id','=','evaluation_forms.semester_id')
            ->whereIn('students.user_id', $userIds)
            ->select(
                'remakings.id',
                'evaluation_forms.id as evaluationFormId',
                'students.user_id as userId',
                'users.name as userName',
                'classes.name as className',
                'semesters.year_from',
                'semesters.year_to',
                'semesters.term',
                'remakings.status',
                'remakings.created_at'
            );

        return DataTables::of($remakings)
        ->editColumn('status', function ($remaking){
            $displayStatus = $this->getDisplayStatusRemaking($remaking->status);
            return $displayStatus;
        })
        ->addColumn('action', function ($remaking) {
            $remakingId = $remaking->id;
            $linkEdit = route('remaking-edit',$remakingId);
            $linkUpdate = route('remaking-update',$remakingId);

            $linkButton = "<a style='color:white' id='btn-reply-remaking-show' class='btn btn-success' 
                                data-remaking-edit-link='$linkEdit'
                                data-remaking-update-link='$linkUpdate'
                                data-remaking-id='$remakingId'
                                 title='Trả lời sau khi phúc khảo'>
                                <i class='fa fa-reply' aria-hidden='true'></i>Trả lời
                                </a>";

            if($remaking->status != RESOLVED){
                $evaluationFormId = $remaking->evaluationFormId;
                $route = route('evaluation-form-show',[$evaluationFormId,'remaking=true&remaking_id='.$remakingId]);
                $linkRemark = "<a class='btn btn-info' href='".$route."'><i class='fa fa-edit' aria-hidden='true' style='color:white'></i>Chấm lại </a>";
                return "<p class='bs-component'>$linkRemark $linkButton </p>";
            }else{
                return "<p class='bs-component'>$linkButton </p>";

            }
        })
        ->make(true);
    }
}
