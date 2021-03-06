<?php

namespace App\Http\Controllers\Evaluation;

use App\Http\Requests\EvaluationFormRequest;
use App\Model\EvaluationCriteria;
use App\Model\EvaluationForm;
use App\Http\Controllers\Controller;

use App\Model\EvaluationResult;
use App\Model\MarkTime;
use App\Model\Notification;
use App\Model\Proof;
use App\Model\Remaking;
use App\Model\Role;
use App\Model\Semester;
use App\Model\Student;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
//    public function create($semesterId)
//    {
//        $user = Auth::user();
//
//        $form = new EvaluationForm();
//        $form->student_id = $user->Student->id;
//        $form->semester_id = $semesterId;
//        $form->save();
//        return view('evaluation-form.index', compact('form', 'user'));
//    }

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
    public function show(Request $request, $id)
    {
        $evaluationForm = EvaluationForm::find($id);
        if (!empty($evaluationForm)) {
            // phân quyền quan trọng. chỉ nhân viên ở khoa nào ms đc xem ở khoa đó.
            $this->authorize($evaluationForm, 'view');

            $user = $this->getUserLogin();

            $evaluationCriterias = EvaluationCriteria::where('level', 1)->get();

            $evaluationResultsTmp = EvaluationResult::where('evaluation_form_id', $id)->get()->toArray();
            // chuyển sang lấy id tiêu chí và id người chấm làm key. để lấy ra heiẻn thị trên form dễ hơn
            $evaluationResults = array();
            foreach ($evaluationResultsTmp as $key => &$val) {
                // key = id tiêu chí + id người chấm.
                $keyResult = $val['evaluation_criteria_id'] . "_" . $val['marker_id'];
                $evaluationResults[$keyResult] = $val;
                unset($evaluationResultsTmp[$key]);
            }

            // lấy role được phép chấm ở thời điểm hiện tại
            $dateNow = Carbon::now()->format('Y/m/d');
            $currentRoleCanMark = DB::table('roles')
                ->leftJoin('mark_times', 'roles.id', '=', 'mark_times.role_id')
                ->where('mark_times.semester_id', $evaluationForm->Semester->id)
                ->whereDate('mark_times.mark_time_start', '<=', $dateNow)
                ->whereDate('mark_times.mark_time_end', '>=', $dateNow)
                ->select('roles.*')
                ->first();

            // nếu đã hết thời gian chấm => k có user nào có thể chấm. thì kiểm tra xem có đang trong thời gian chấm phcú khảo k?
            // nếu có thì gán vào role có thể phúc khảo.
            // tạm thời sẽ gán vào role là role cua co van hoc tap
            //form nào đã yêu cầu phúc khảo thì mới đc phép chấm
            if (empty($currentRoleCanMark)) {

                if ($this->checkInTime($evaluationForm->Semester->date_start_to_re_mark, $evaluationForm->Semester->date_end_to_re_mark)) {
                    if (!empty($evaluationForm->Remaking)) {
                        // nếu form này có tồn tại remaking => đã yêu cầu phcú khảo. cho chấm lại
                        $currentRoleCanMark = Role::whereHas('permissions', function ($query) {
                            $query->where('name', 'like', '%can-mark%');
                        })->where('weight', ROLE_COVANHOCTAP)->first();
                    }
                }
                if (empty($currentRoleCanMark)) {
                    // nếu hết thời gian chấm => k có role nào còn chấm
                    // và cũng k trong thời gian phúc khảo
                    // => gán quyền chấm cho admin => k được gì nhưng để bỏ qua lỗi
                    $currentRoleCanMark = Role::where('weight', ROLE_ADMIN)->first();
                }
            }

            //KIỂM TRA XEM. NẾU NGƯỜI ĐANG CHẤM HIỆN TẠI CHƯA CHẤM ĐIỂM CHO FORM. NGƯỜI NÀY PHẢI KHÁC ROLE SINH VIÊN.
            // và hiện tại người này có thể chấm.
            // THÌ SẼ SET ĐIỂM CHO CÁC Ô INPUT = ĐIỂM CỦA NGƯỜI CHẤM TRƯỚC ĐÓ
            $evaluationResultsOfCurrentUserLogin = EvaluationResult::where('evaluation_form_id', $id)->where('marker_id', $user->users_id)->get()->toArray();

            if (empty($evaluationResultsOfCurrentUserLogin) AND $currentRoleCanMark->weight == $user->Role->weight) {
                $evaluationResultsOfCurrentUserLoginTmp = array();
                foreach ($evaluationResults as $key => $val) {
                    $keyResult = $val['evaluation_criteria_id'] . "_" . $user->users_id;
                    $val['marker_id'] = $user->users_id;
                    $evaluationResultsOfCurrentUserLoginTmp[$keyResult] = $val;
                }
                $evaluationResults = array_merge($evaluationResults, $evaluationResultsOfCurrentUserLoginTmp);

                // đánh dấu là role này chưa chấm. để qua bên kia kiẻm tra
                $isMark = false;
            } else {
                $isMark = true;
            }

            //lấy ra danh sách các role có thể chấm điểm để hiển thị các ô input
            $rolesCanMark = Role::whereHas('permissions', function ($query) {
                $query->where('name', 'like', '%can-mark%');
            })->select('id', 'name', 'display_name', 'weight')->orderBy('id')->get()->toArray();

            //lấy danh sách Id role can mark
            $arrRoleId = array();
            foreach ($rolesCanMark as $key => $value) {
                $arrRoleId[] = $value['id'];
            }

            // lấy ra danh sách các role và user
            $listUserMark = DB::table('roles')
                ->leftJoin('users', 'users.role_id', '=', 'roles.id')
                ->leftJoin('evaluation_results', 'evaluation_results.marker_id', '=', 'users.users_id')
                ->select('users.users_id as userId', 'roles.weight as userRole', 'roles.*')
                ->where('evaluation_results.evaluation_form_id', $id)
                ->whereIn('roles.id', $arrRoleId)
                ->groupBy('roles.id')
                ->orderBy('roles.id')
                ->get()->toArray();

            // gộp mảng id và mảng user lại. nếu user nào k có thì cho rỗng. vẫn giữ id để hiển thị fỏm input
            $listUserMarkTmp = array();
            if (count($rolesCanMark) != count($listUserMark)) {
                for ($i = 0; $i < count($rolesCanMark); $i++) {
                    $tmp = null;
                    for ($j = 0; $j < count($listUserMark); $j++) {
                        if ($listUserMark[$j]->name == $rolesCanMark[$i]['name']) {
                            $tmp = $listUserMark[$j];
                            break;
                        }
                    }
                    if (!empty($tmp)) {
                        $listUserMarkTmp [] = [
                            'userId' => $tmp->userId,
                            'userRole' => $tmp->userRole,
                            'name' => $tmp->name,
                            'display_name' => $tmp->display_name
                        ];
                    } else {
                        $listUserMarkTmp [] = [
                            'userId' => null,
                            'userRole' => $rolesCanMark[$i]['id'],
                            'name' => $rolesCanMark[$i]['name'],
                            'display_name' => $rolesCanMark[$i]['display_name']
                        ];
                    }
                }
            } else {
                for ($i = 0; $i < count($rolesCanMark); $i++) {
                    $listUserMarkTmp [] = [
                        'userId' => $listUserMark[$i]->userId,
                        'userRole' => $listUserMark[$i]->userRole,
                        'name' => $listUserMark[$i]->name,
                        'display_name' => $listUserMark[$i]->display_name
                    ];
                }
            }
            $listUserMark = $listUserMarkTmp;

            $monitor = Student::leftJoin('users','students.user_id','=','users.users_id')
                ->leftJoin('roles','users.role_id','=','roles.id')
                ->where('class_id',$evaluationForm->Student->class_id)
                ->where('roles.weight',ROLE_BANCANSULOP)
                ->first();


            //danh sách minh chứng
            $proofs = Proof::where([
                'semester_id' => $evaluationForm->Semester->id,
                'created_by' => $evaluationForm->Student->id
            ])->get();

            if (!empty($request->remaking_id)) {

                $remaking = Remaking::find($request->remaking_id);
                if (empty($remaking)) {
                    return redirect()->back();
                } else {
                    return view('evaluation-form.show', compact('isMark', 'evaluationForm', 'user', 'evaluationCriterias', 'listUserMark', 'evaluationResults', 'currentRoleCanMark', 'proofs', 'remaking','monitor'));
                }
            }


            /// evaluationForm : form đang đánh giá,
            /// $user : đang đăng nhập
            /// evaluationCriterias : cáctiêu chí đánh giá,
            /// listUserMark : danh sách user và quyền. nếu đã chấm thì lấy ra userid. nếu chưa chấm thì userId=  rrỗng
            /// evaluationResults : mảng :key là id tiếu chí + userId ng chấm. value: điểm,...
            /// currentRoleCanMark : xác định role có thể chấm ở thời điểm hiện tại
            /// proofs. danh sách minh chứng của user.
            ///$monitor : ban cán sự lớp
            return view('evaluation-form.show', compact('isMark', 'evaluationForm', 'user', 'evaluationCriterias', 'listUserMark', 'evaluationResults', 'currentRoleCanMark', 'proofs','monitor'));
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
        $arrRules = array();
        $evaluationCriterias = EvaluationCriteria::whereIn('id', ARRAY_EVALUATION_CRITERIAS_VALIDATE)->get();
        foreach ($evaluationCriterias as $key => $value) {
            $arrRules["score$value->id"] = "required|between:$value->mark_range_from,$value->mark_range_to|numeric";
        }
        $arrRules['totalScoreOfForm'] = "required|numeric|between:0,100";
        $this->validate($request, $arrRules);

        $evaluationFormId = $id;
        $evaluationForm = EvaluationForm::find($evaluationFormId);
        $userLogin = $this->getUserLogin();

        // lưu điểm đánh giá
        $arrEvaluationResult = array();
        $arrProof = array();

        // xóa điểm cũ nếu đã chấm.
        $isMarked = EvaluationResult::where([
            'evaluation_form_id' => $evaluationFormId,
            'marker_id' => $userLogin->users_id
        ])->first();

        // nếu là chấm phúc khảo thì lưu lại điểm cũ
//        $isRemaking = $this->checkInTime($evaluationForm->Semester->date_start_to_re_mark,$evaluationForm->Semester->date_end_to_re_mark);
        $remaking = Remaking::find($request->remakingId);
        if (!empty($remaking)) {
            $arrScoreOld = EvaluationResult::where([
                'evaluation_form_id' => $evaluationFormId,
                'marker_id' => $userLogin->users_id
            ])->select('evaluation_criteria_id', 'evaluation_form_id', 'marker_id', 'marker_score')->get()->toArray();
        }

        // sau khi lấy đc giá trị điểm cũ r. thì kiểm tra điểm coi có phải chấm lại hay k rồi xóa
        // nếu chấm rồi thì xóa hết điểm r thêm lại
        if (!empty($isMarked)) {
            EvaluationResult::where([
                'evaluation_form_id' => $evaluationFormId,
                'marker_id' => $userLogin->users_id
            ])->delete();
        }
        foreach ($request->all() as $key => $value) {
            if ($key != '_token') {
                if (substr($key, "0", "5") == "score") {
                    $evaluationCriteriaId = (int)substr($key, "5", "7");
                    // mỗi form input điểm sẽ có tên = score + id tiêu chí.
                    // lấy ra rồi lưu 1 lần 1 mảng cho nhanh.
                    // nếu chưa chấm thì là thêm vào 1 mảng để them vào db cho nhanh.

                    $arrEvaluationResult[] = [
                        'evaluation_criteria_id' => $evaluationCriteriaId,
                        'evaluation_form_id' => $evaluationFormId,
                        'marker_id' => $userLogin->users_id,
                        'marker_score' => $value
                    ];

                } elseif (substr($key, "0", "5") == "proof") {
                    $evaluationCriteriaId = (int)substr($key, "5", "7");
                    foreach ($value as $proof) {
                        $fileName = str_random(13) . "_" . $proof->getClientOriginalName();
                        $fileName = $this->convert_vi_to_en(preg_replace('/\s+/', '', $fileName));
                        while (file_exists("upload/proof/" . $fileName)) {
                            $fileName = $this->convert_vi_to_en(str_random(13) . "_" . $fileName);
                        }
                        $proof->move('upload/proof/', $fileName);  // lưu file vào thư mục
                        $arrProof[] = [
                            'name' => $fileName,
                            'semester_id' => $evaluationForm->semester_id,
                            'created_by' => $userLogin->Student->id,
                            'evaluation_criteria_id' => $evaluationCriteriaId
                        ];
                    }
                }
            }
        }

        $evaluationForm->EvaluationResults()->createMany($arrEvaluationResult);
        $evaluationForm->total = $request->totalScoreOfForm;


        //cập nhật lại trnagj thái của form
        // tính  = 1,2,4,8,16.

        // lấy ra số role có thể chấm. so sánh với số người đã chấm trong form
        // nếu = nhau thì thay đổi status form.  -> RATED
        $rolesCanMark = Role::whereHas('permissions', function ($query) {
            $query->where('name', 'like', '%can-mark%');
        })->get();
        $arrEvaluationResult = EvaluationResult::where('evaluation_form_id', $evaluationFormId)->groupBy('marker_id')->get();
        if (count($rolesCanMark) == count($arrEvaluationResult)) {
            $evaluationForm->status = -1;
        } else {
            //nếu trả về true nghĩa là user này đã chấm => đã chấm thì k + status lên
            if ($this->getStatusEvaluationForm($evaluationForm->status) == false) {
                $evaluationForm->status += pow('2', $userLogin->Role->weight - 1);
            }
        }
        $evaluationForm->save();

        if ($arrProof) {
            $semester = Semester::find($evaluationForm->semester_id);
            $semester->Proofs()->createMany($arrProof);
        }

        // lưu lại điểm cũ vào remaking nếu phúc khảo. sau đó chuyển vê trang remaking
        if (!empty($remaking)) {
            $remaking->update(['old_score' => json_encode($arrScoreOld)]);
            return redirect()->route('remaking', 'user_id=' . $remaking->EvaluationForm->Student->user_id);
        }

        return redirect()->back()->with(['flash_message_success' => 'Chấm điểm thành công']);

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

    public static function checkRank($score)
    {
        if ($score >= EXCELLENT) {
            return "Xuất sắc";
        } elseif ($score >= VERY_GOOD) {
            return "Tốt";
        } elseif ($score >= GOOD) {
            return "Khá";
        } elseif ($score >= AVERAGE) {
            return "Trung bình";
        } elseif ($score >= POOR) {
            return "Yếu";
        } elseif ($score >= BAD) {
            return "Kém";
        }
    }

    public function checkInput(Request $request)
    {

        $evaluationCriteria = EvaluationCriteria::find($request->ecId);
        if (!empty($evaluationCriteria)) {
            if ($evaluationCriteria->mark_range_from <= $request->value AND $request->value <= $evaluationCriteria->mark_range_to) {
                return response()->json([
                    'status' => true,
                ], 200);
            } else {
                $score = null;
                //nếu điểm chấm < điểm min => điểm chấm = điểm min
                if ($request->value < $evaluationCriteria->mark_range_from) {
                    $score = $evaluationCriteria->mark_range_from;

                    //nếu điểm chấm > điểm max => điểm chấm = điểm max
                } elseif ($request->value > $evaluationCriteria->mark_range_to) {
                    $score = $evaluationCriteria->mark_range_to;
                }
                // nếu điểm k hợp lệ. thì kiểm tra. nếu điểm nhập gần min thì set = min. nếu gần max thì set = max
                return response()->json([
                    'status' => false,
                    'score' => $score,
                    'message' => "Khoảng điểm của tiêu chí này là từ $evaluationCriteria->mark_range_from -> $evaluationCriteria->mark_range_to",
                ], 200);
            }
        }
        // nếu k tìm thấy tiêu chí thì trả về điểm = 0;
        return response()->json([
            'score' => 0,
            'message' => 'Vui lòng không chỉnh sửa code'
        ], 200);
    }

}
