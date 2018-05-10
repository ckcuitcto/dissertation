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
use Illuminate\Support\Facades\DB;
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

        // phân quyền quan trọng. chỉ nhân viên ở khoa nào ms đc xem ở khoa đó.
        $this->authorize($evaluationForm,'view');

        $user = Auth::user();
        $evaluationCriterias = EvaluationCriteria::where('level', 1)->get();

        $evaluationResultsTmp = EvaluationResult::where('evaluation_form_id',$id)->get()->toArray();
        // chuyển sang lấy id tiêu chí và id người chấm làm key. để lấy ra heiẻn thị trên form dễ hơn
        $evaluationResults = array();
        foreach($evaluationResultsTmp as $key => &$val){
            // key = id tiêu chí + id người chấm.
            $keyResult = $val['evaluation_criteria_id']."_".$val['marker_id'];
            $evaluationResults[$keyResult] = $val;
            unset($evaluationResultsTmp[$key]);
        }

        //lấy ra danh sách các role có thể chấm điểm để hiển thị các ô input
        $rolesCanMark = Role::whereHas('permissions', function ($query) {
            $query->where('name', 'like', '%can-mark%');
        })->select('id','name','display_name')->orderBy('id')->get()->toArray();
//        $rolesCanMark = array_flatten($rolesCanMark);

        //lấy danh sách Id role can mark
        $arrRoleId = array();
        foreach($rolesCanMark as $key => $value){
            $arrRoleId[] = $value['id'];
        }

        // lấy ra danh sách các role và user
        $listUserMark = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->leftJoin('evaluation_results', 'evaluation_results.marker_id', '=', 'users.id')
            ->select('users.id as userId','users.role_id as userRole', 'roles.*')
            ->where('evaluation_results.evaluation_form_id', $id)
            ->whereIn('roles.id', $arrRoleId)
            ->groupBy('roles.id')
            ->orderBy('roles.id')
            ->get()->toArray();


        // gộp mảng id và mảng user lại. nếu user nào k có thì cho rỗng. vẫn giữ id để hiển thị fỏm input
        $listUserMarkTmp = array();
        if(count($rolesCanMark) != count($listUserMark)){
            for($i = 0; $i < count($rolesCanMark); $i++){
//                var_dump($listUserMark[$i]);
                if(!empty($listUserMark[$i])){
                    $listUserMarkTmp [] = [
                        'userId' => $listUserMark[$i]->userId,
                        'userRole' => $listUserMark[$i]->userRole,
                        'name' => $listUserMark[$i]->name,
                        'display_name' => $listUserMark[$i]->display_name
                    ];
                }else{
                    $listUserMarkTmp [] = [
                        'userId' => null,
                        'userRole' => $rolesCanMark[$i]['id'],
                        'name' => $rolesCanMark[$i]['name'],
                        'display_name' => $rolesCanMark[$i]['display_name']
                    ];
                }
            }
        }
        $listUserMark = $listUserMarkTmp;
//        var_dump($rolesCanMark);
//        dd($evaluationResults);

        return view('evaluation-form.show', compact('evaluationForm', 'user', 'evaluationCriterias', 'listUserMark','evaluationResults'));
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
                        'marker_id' => $userLogin->id,
                        'marker_score' => $value
                    ];
                } elseif (substr($key, "0", "5") == "proof") {
                    $evaluationCriteriaId = (int)substr($key, "5", "7");
                    $arrProof[] = [
                        'name' => $value->getClientOriginalExtension(),
                        'semester_id' => $evaluationForm->semester_id,
                        'created_by' => $userLogin->Student->id,
                        'evaluation_criteria_id' => $evaluationCriteriaId
                    ];
                }
            }
        }
        $evaluationForm->EvaluationResults()->createMany($arrEvaluationResult);
        $evaluationForm->total = $request->totalScoreOfForm;
        $evaluationForm->save();

        if($arrProof) {
            $semester = Semester::find($evaluationForm->semester_id);
            $semester->Proofs()->createMany($arrProof);
        }

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
