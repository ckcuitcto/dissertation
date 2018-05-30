<?php

namespace App\Http\Controllers\Evaluation;

use App\Model\EvaluationForm;
use App\Model\Remaking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReMakingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userLogin = Auth::user();
        $userIds = $this->getStudentByRoleUserLogin($userLogin,true);

        // lấy ra danh sách id của student
        foreach ($userIds as $key => $value) {
            $arrId[] = $value->id;
        }
        $remakings = Remaking::join('evaluation_forms','remakings.evaluation_form_id','=','evaluation_forms.id')
//            ->join('students','students.id','=','evaluation_forms.student_id')
            ->whereIn('evaluation_forms.student_id', $arrId)->select('remakings.*')->get();
        return view('remaking.index', compact('remakings'));
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
}
