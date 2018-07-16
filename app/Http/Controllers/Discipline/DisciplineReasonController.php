<?php

namespace App\Http\Controllers\Discipline;

use App\DisciplineReason;
use App\Model\Discipline;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;


class DisciplineReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'evaluation_criteria_id' => 'required|exists:evaluation_criterias,id',
            'score_minus' => 'required|min:0|max:100|numeric',
            'reason' => 'required'
        ], [
            "evaluation_criteria_id.required" => 'Bắt buộc chọn tiêu chí',
            "evaluation_criteria_id.exists" => 'Tiêu chí không tồn tại',
            "score_minus.required" => 'Bắt buộc nhập số điểm trừ',
            "score_minus.min" => 'Số điểm trừ phải lớn hơn 0',
            "score_minus.max" => 'Số điểm trừ phải nhỏ hơn 100',
            "score_minus.numeric" => 'Số điểm trừ phải là số',
            "reason.required" => 'Bắt buộc nhập nội dung kỷ luật'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $data = request()->except(['_token']);
            DisciplineReason::insert($data);
            return response()->json([
                'status' => true
            ], 200);
        }
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
        $disciplineReason = DisciplineReason::find($id);

        if(empty($disciplineReason)){
            return response()->json([
                'status' => false
            ], 200);
        }
        return response()->json([
            'disciplineReason' => $disciplineReason,
            'status' => true
        ], 200);
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
            'evaluation_criteria_id' => 'required|exists:evaluation_criterias,id',
            'score_minus' => 'required|min:0|max:100|numeric',
            'reason' => 'required'
        ], [
            "evaluation_criteria_id.required" => 'Bắt buộc chọn tiêu chí',
            "evaluation_criteria_id.exists" => 'Tiêu chí không tồn tại',
            "score_minus.required" => 'Bắt buộc nhập số điểm trừ',
            "score_minus.min" => 'Số điểm trừ phải lớn hơn 0',
            "score_minus.max" => 'Số điểm trừ phải nhỏ hơn 100',
            "score_minus.numeric" => 'Số điểm trừ phải là số',
            "reason.required" => 'Bắt buộc nhập nội dung kỷ luật'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
//            $data = request()->except(['_token',]);
            $disciplineReason = DisciplineReason::find($id);
            $disciplineReason->score_minus = $request->score_minus;
            $disciplineReason->reason = $request->reason;
            $disciplineReason->evaluation_criteria_id = $request->evaluation_criteria_id;
            $disciplineReason->save();
            return response()->json([
                'status' => true
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $disciplineReason = DisciplineReason::find($id);
        if (!empty($disciplineReason)) {
            $disciplinesByDisciplineReason = Discipline::where('discipline_reason_id',$id)->count();
            if($disciplinesByDisciplineReason <= 0 ) {
                $disciplineReason->delete();
                return response()->json([
                    'disciplineReason' => $disciplineReason,
                    'status' => true
                ], 200);
            }
        }
        return response()->json([
            'status' => false
        ], 200);
    }

    public function ajaxGetDisciplineReason(){
        $disciplineReason = DB::table('discipline_reasons')
            ->leftJoin('evaluation_criterias','discipline_reasons.evaluation_criteria_id','=','evaluation_criterias.id')
            ->select('discipline_reasons.id','evaluation_criterias.content','discipline_reasons.score_minus','discipline_reasons.reason');

        return DataTables::of($disciplineReason)
            ->addColumn('action', function ($dr) {
                $linkEdit = route('discipline-reason-edit',$dr->id);
                $linkUpdate = route('discipline-reason-update',$dr->id);
                $buttonEdit = "<button title='Sửa' data-id='$dr->id' data-edit-link='$linkEdit' data-update-link='$linkUpdate'  class='btn btn-primary update-discipline-reason'>
                   <i class='fa fa-edit' aria-hidden='true'></i></button>";

                $disciplinesByDisciplineReason = Discipline::where('discipline_reason_id',$dr->id)->count();
                if($disciplinesByDisciplineReason <= 0 ) {
                    $linkDelete = route('discipline-reason-destroy', $dr->id);
                    $buttonDelete = "<button title='Xóa' data-id='$dr->id' data-delete-link='$linkDelete' class='btn btn-danger delete-discipline-reason'>
                   <i class='fa fa-trash' aria-hidden='true'></i></button>";
                    return "<span class='bs-component'>$buttonEdit $buttonDelete</span> ";
                }
                return "<span class='bs-component'>$buttonEdit</span> ";
            })
            ->make(true);
    }
}
