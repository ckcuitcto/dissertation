<?php

namespace App\Http\Controllers\Evaluation;

use App\Model\EvaluationCriteria;
use App\Model\EvaluationForm;
use App\Http\Controllers\Controller;

use App\Model\EvaluationResult;
use App\Model\Role;
use App\Model\Semester;
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
        return view('evaluation-form.index', compact('topics'));
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
        return view('evaluation-form.index', compact('form', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evaluationForm = EvaluationForm::find($id);
        $evaluationCriterias = EvaluationCriteria::where('level', 1)->get();
        $user = Auth::user();
        //lấy ra danh sách các role có thể chấm điểm để hiển thị các ô input
        $listRoleCanMark = Role::whereHas('permissions', function ($query) {
            $query->where('name', 'like', '%can-mark%');
        })->get();
        return view('evaluation-form.show', compact('evaluationForm', 'user', 'evaluationCriterias', 'listRoleCanMark'));
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
        $evaluationFormId = $id;
        $evaluationForm = EvaluationForm::find($evaluationFormId);
        $userLogin = Auth::user();

//        dd($request->all());
        // lưu điểm đánh giá
        $arrEvaluationResult = array();
        $arrProof = array();
        foreach ($request->all() as $key => $value) {
            if ($key != '_token') {
                if (substr($key, "0", "5") == "score") {

                    // mỗi form input điểm sẽ có tên = score + id tiêu chí.
                    // lấy ra rồi lưu 1 lần 1 mảng cho nhanh.
                    $evaluationCriteriaId = (int)substr($key, "5", "7");
                    $arrEvaluationResult[] = [
                        'evaluation_criteria_id' => $evaluationCriteriaId,
                        'evaluation_form_id' => $evaluationFormId,
                        'marker_id' => $userLogin->Student->id,
                        'marker_score' => $value
                    ];
                } elseif (substr($key, "0", "5") == "proof") {
                    $evaluationCriteriaId = (int)substr($key, "5", "7");
                    $arrProof[] = [
                        'name' => $value->getClientOriginalExtension(),
                        'semester_id' => $evaluationForm->semester_id,
                        'created_by' => $userLogin->id,
                        'evaluation_criteria_id' => $evaluationCriteriaId
                    ];
                }
            }
        }
        $evaluationForm->EvaluationResults()->createMany($arrEvaluationResult);
        $semester = Semester::find($evaluationForm->semester_id);
        $semester->Proofs()->createMany($arrProof);

        return redirect()->back()->with(['flash_message' => 'Chấm điểm thành công']);

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

    public static function handleDetail($str)
    {
        $arrStr = explode(';', $str);
        $value = $title = "<tr>";

        foreach ($arrStr as $item) {
            $arrValue = explode(':', $item);
            $title .= "<th>" . $arrValue[0] . "</th>";
            $value .= "<td>" . $arrValue[1] . "</td>";
        }
        $html = "<table border='0' style='width:-webkit-fill-available '> " . $title . "</tr>" . $value . "</tr> </table>";
        return $html;
    }

    public function checkFileUpload(Request $request)
    {

        $arrFileType = array('xlsx', 'doc', 'docx', 'img', 'jpg', 'pdf', 'png', 'jpeg', 'bmp');

        $arrFile = $request->file('fileUpload');
        foreach ($arrFile as $file) {
            if (!in_array($file->getClientOriginalExtension(), $arrFileType)) {
                $arrMessage = array("fileImport" => ["File " . $file->getClientOriginalName() . " không hợp lệ "]);
                return response()->json([
                    'status' => false,
                    'arrMessages' => $arrMessage
                ], 200);
            }
        }
        return response()->json([
            'status' => true,
        ], 200);
//
//        if($file){
//
//            if(!in_array($file->getClientOriginalExtension(),$arrFileType))
//            {
//                $arrMessage = array("fileImport" => ["File ".$file->getClientOriginalName()." không hợp lệ "] );
//                return response()->json([
//                    'status' => false,
//                    'arrMessages' => $arrMessage
//                ], 200);
//            }else{
//                return response()->json([
//                    'status' => true,
//                ], 200);
//            }
//        }
    }


}
