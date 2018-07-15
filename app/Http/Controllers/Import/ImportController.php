<?php

namespace App\Http\Controllers\Import;

use App\Model\AcademicTranscript;
use App\Model\Discipline;
use App\Model\EvaluationCriteria;
use App\Model\FileImport;
use App\Model\Semester;
use App\Model\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Validator;
use Illuminate\Support\Facades\File;

class ImportController extends Controller
{
    public function index(){
        $imports = FileImport::all();
        return view('import.index',compact('imports'));
    }

    public function discipline(){
        $semesters = Semester::where('date_end_to_mark','<',Carbon::now()->format(DATE_FORMAT_DATABASE))->orderBy('id','DESC')->get();
        return view('discipline.index',compact('semesters'));
    }

    public function ajaxGetDiscipline(Request $request){

        $disciplines = DB::table('disciplines')
            ->leftJoin('students', 'disciplines.user_id', '=', 'students.user_id')
            ->leftJoin('users', 'students.user_id', '=', 'users.users_id')
            ->leftJoin('semesters', 'semesters.id', '=', 'disciplines.semester_id')
            ->leftJoin('evaluation_criterias', 'evaluation_criterias.id', '=', 'disciplines.evaluation_criteria_id')
            ->select(
                'disciplines.*',
                'users.name as userName',
                'semesters.year_from',
                'evaluation_criterias.content',
                DB::raw("CONCAT('Học kì: ',semesters.term,'*** Năm học: ',semesters.year_from,' - ',semesters.year_to) as semester")
            );

        return DataTables::of($disciplines)
            ->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $file = FileImport::find($id);
        if (!empty($file)) {
//            unlink()
            $file->delete();
            //sau khi xóa học kì thì cũng xóa form đánh giá
            return response()->json([
                'file' => $file,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }

    public function importDiscipline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileImport' => 'required|',
        ], [
            'fileImport.required' => 'Bắt buộc chọn file',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {
            $file = $request->file('fileImport');
            if ($file->getClientOriginalExtension() != "xlsx") {
                $arrMessage = array("fileImport" => ["File " . $file->getClientOriginalName() . " không hợp lệ "]);
                return response()->json([
                    'status' => false,
                    'arrMessages' => $arrMessage
                ], 200);
            }
            $fileName = $this->convert_vi_to_en(str_random(8) . "_" . $file->getClientOriginalName());
            while (File::exists(STUDENT_PATH . $fileName)) {
                    $fileName = $this->convert_vi_to_en(str_random(8) . "_" . $file->getClientOriginalName());
            }
            $file->move(STUDENT_PATH, $fileName);
            $arrFileName[] = $fileName;
            $dataFileExcel = \Maatwebsite\Excel\Facades\Excel::selectSheets('Sheet1')->load(STUDENT_PATH . $fileName, function ($reader) {
            })->noHeading()->get();
            $semesterId = $request->semester_id;
            $arrDiscipline = array();
            for ($i = 4; $i < count($dataFileExcel); $i++) {

                if (!empty($dataFileExcel[$i][4]) AND !empty($dataFileExcel[$i][0]) ) {
                    // lấy khoa theo Id
                    // cột 0 là stt
                    // cột 1: họ và tên
                    // cột 2: năm sinh
                    // cột 3 : lớp
                    // cột 4: mssv
                    // cột 5: nội dung vi phạm
                    // cột 6: hình thức kỉ luật
                    // cột 7 : ghi chú
                    // cột 8 : tiêu chí bị trừ điểm
                    $userId = $dataFileExcel[$i][4];
                    $student = Student::where('user_id', $userId)->first();
                    if (empty($student)) {
                        $arrError[] = "Sinh viên " . $dataFileExcel[$i][1] . "- $userId không tồn tại";
                    }else{
                        $content = $dataFileExcel[$i][5];
                        $hinhthuckiluat = $dataFileExcel[$i][6];
                        $note = $dataFileExcel[$i][7];
                        $evaluationCriteriaId = ARRAY_ACADEMIC_TRANSCRIPT_TO_EVALUATION_CRITERIA[$dataFileExcel[$i][8]];

                        // nếu để key là userid thì nếu 1 sinh viên bị nheièu loại kỉ luật
                        // thì sẽ bị trùng và chỉ lưu đc kỉ luật cuối
                        $arrDiscipline[] = [
                            'semester_id' => $semesterId,
                            'user_id' => $userId,
                            'evaluation_criteria_id' => $evaluationCriteriaId,
                            'score_minus' => SCORE_MINUS,
                            'reason' => "Nội dung vi phạm: $content.  Hình thức kỷ luật: $hinhthuckiluat . Ghi chú: $note"
                        ];
                    }
                }
            }

            if(!empty($arrDiscipline) AND empty($arrError)) {
                $userLogin = $this->getUserLogin();
                FileImport::insert(array(
                    'file_path' => $fileName,
                    'file_name' => $file->getClientOriginalName(),
                    'status'    => 'Thành công',
                    'staff_id'  => $userLogin->Staff->id,
                    'semester_id' => $semesterId
                ));
//                Discipline::insert($arrDiscipline);

                $arrScore = $this->getScoreListBySemester($semesterId,$arrDiscipline);
                $arrScore = array_map(function($tag) {
                    return array(
                        'user_id' => $tag['0'],
                        'semester_id' => $tag['1'],
                        'class_id' => $tag['2'],
                        'score_ia' => $tag['3'],
                        'score_ib' => $tag['4'],
                        'score_ic' => $tag['5'],
                        'score_ii' => $tag['6'],
                        'score_iii' => $tag['7'],
                        'score_iv' => $tag['8'],
                        'score_v' => $tag['9'],
                        'note' => $tag['10'],
                    );
                }, $arrScore);

                if(!empty($arrScore)) {

                    // di chuyển và xóa file cũ
                    if(File::move(STUDENT_PATH.$fileName,STUDENT_PATH_STORE.$fileName)) {
                        if (file_exists(STUDENT_PATH . $fileName)) {
                            unlink(STUDENT_PATH . $fileName);
                        }
                    }

                    // trừ điểm của sinh viên đó tại đây
                    // chạy vòng lặp các sinh viên bị kỉ luật.
                    // lấy ra sinh viên đó ở bảng điểm trừ điểm. sau đó mới thêm vào bảng bảng điểm
                    // khóa chính của mỗi valua trong mảng đều là mã số sinh viên
                    foreach ($arrDiscipline as $value) {
                        Discipline::updateOrCreate(
                            [
                                'user_id' => $value['user_id'],
                                'semester_id' => $value['semester_id'],
                                'evaluation_criteria_id' => $value['evaluation_criteria_id'],
                            ],
                            [
                                'score_minus' => $value['score_minus'],
                                'reason' => $value['reason']
                            ]
                        );

                        // lấy ra tiêu chí ( đã đc map với cột ở bảng bảng điểm. để xem tiêu chí bị kỉ luật là cái nào
                        // sau đó trừ điểm theo tiêu chí đó.
                        $evaluation_criteria_id = ARRAY_EVALUATION_CRITERIA_VS_ACADEMIC_TRANSCRIPT[$value['evaluation_criteria_id']];
                        $arrScore[$value['user_id']][$evaluation_criteria_id] -= $value['score_minus'];
                        if (!empty($arrScore[$value['user_id']]['note'])) {
                            $arrScore[$value['user_id']]['note'] .= "-" . $value['reason'];
                        } else {
                            $arrScore[$value['user_id']]['note'] = $value['reason'];
                        }
                    }

                    // xóa hết dữ liệu cũ rồi thêm mới.
                    AcademicTranscript::where('semester_id', $semesterId)->delete();
                    AcademicTranscript::insert($arrScore);

                    // sau khi nhập file kỉ luật. thì sẽ lưu điểm vào bảng bảng điểm.
                    return response()->json([
                        'status' => true
                    ], 200);
                } else {
                    unlink(STUDENT_PATH . $fileName);
                    $arrError[] = 'Danh sách sinh viên đã chấm trong học kì rỗng';
                    return response()->json([
                        'status' => false,
                        'errors' => $arrError
                    ], 200);
                }
            } else {
                unlink(STUDENT_PATH . $fileName);
                $arrError[] = 'Import không thành công hoặc file lỗi';
                return response()->json([
                    'status' => false,
                    'errors' => $arrError
                ], 200);
            }
        }
    }

    protected function getScoreListBySemester($semesterId,$arrDiscipline){

        // $arrDiscipline lấy mảng này để lấy reason
        $arrUserId = DB::table('student_list_each_semesters')->where('semester_id',$semesterId)->select('user_id','class_id')->get()->toArray();
        $arrScoreAllUser = array(); // mảng này lưu tất cả điểm của sinh viên
        for ($i = 0; $i < count($arrUserId); $i++) {
            $userId = $arrUserId[$i]->user_id;
            $classId = $arrUserId[$i]->class_id;
            $arrScore = array(); // tạo mảng mới. để nếu sinh viên sau chưa chấm. sẽ k bị gán giá trị của sv trước

            $arrUserInfo = array($userId, $semesterId,$classId);

            // lấy ra điểm của form
            // với kết quả có thời gian chấm trễ nhất. chỉ lấy level1 = tiêu chí
            //litmit(4) vì có 5 cái level. nhưng bỏ qua cái đầu tiên nên chỉ lấy 4
            $resultLevel1 = DB::table('evaluation_criterias')
                ->leftJoin('evaluation_results', 'evaluation_results.evaluation_criteria_id', '=', 'evaluation_criterias.id')
                ->leftJoin('evaluation_forms', 'evaluation_forms.id', '=', 'evaluation_results.evaluation_form_id')
                ->leftJoin('students', 'students.id', '=', 'evaluation_forms.student_id')
                ->where([
                    ['evaluation_criterias.level', 1],
                    ['students.user_id', $userId],
                    ['evaluation_forms.semester_id', $semesterId],
                    ['evaluation_criterias.id', '<>', YTHUCTHAMGIAHOCTAP_ID]
                ])
                ->select(
                    'evaluation_results.marker_score',
                    'evaluation_results.marker_id',
                    'evaluation_forms.total'
                )
                ->orderBy('evaluation_results.created_at', 'DESC')
                ->orderBy('evaluation_criterias.id', 'ASC')
                ->limit(4)->get()->toArray();
            if (!empty($resultLevel1)) {
                $markerId = $resultLevel1[0]->marker_id;
                //id người chấm thì giống nhau. mên lấy id của ngươi ở lv1 đem xuống lv2 luôn
                $resultLevel2 = DB::table('evaluation_criterias')
                    ->leftJoin('evaluation_results', 'evaluation_results.evaluation_criteria_id', '=', 'evaluation_criterias.id')
                    ->leftJoin('evaluation_forms', 'evaluation_forms.id', '=', 'evaluation_results.evaluation_form_id')
                    ->leftJoin('students', 'students.id', '=', 'evaluation_forms.student_id')
                    ->where([
                        ['evaluation_results.marker_id', $markerId],
                        ['evaluation_forms.semester_id', $semesterId],
                        ['students.user_id', $userId],
                    ])
                    ->whereIn('evaluation_criterias.parent_id', EVALUATION_CRITERIAS_CHILD_PARENT_1)
                    ->select(
                        DB::raw('SUM(evaluation_results.marker_score) as marker_score')
                    )
                    ->orderBy('evaluation_criterias.parent_id', 'ASC')
                    ->groupBy('evaluation_criterias.parent_id')
                    ->get()->toArray();

                $arrScore = array_merge($resultLevel2, $resultLevel1);

                $arrScoreTmp = array();
                foreach ($arrScore as $value) {
                    $arrScoreTmp[] = $value->marker_score;
                }

                // k cần thêm note ở đây // tự thêm vào sau
//                $arrScoreTmp[] = (!empty($arrDiscipline[$userId]['reason'])) ? $arrDiscipline[$userId]['reason'] : "";
                $arrScoreTmp[] = "";
                $arrScoreTmp = array_merge($arrUserInfo,$arrScoreTmp);
                $arrScore = $arrScoreTmp;
            }

            if (empty($arrScore)) {
//                $arrScore = array(0, 0, 0, 0, 0, 0, 0,(!empty($arrDiscipline[$userId]['reason'])) ? $arrDiscipline[$userId]['reason'] : "" );
                $arrScore = array(0, 0, 0, 0, 0, 0, 0, '');
                $arrScore = array_merge($arrUserInfo,$arrScore);
            }
            $arrScoreAllUser[$userId] = $arrScore;
        }
        return $arrScoreAllUser;
    }

    public function ajaxGetFiles(Request $request){

        $proofs = DB::table('file_imports')
            ->leftJoin('staff','staff.id','=','file_imports.staff_id')
            ->leftJoin('users','users.users_id','=','staff.user_id')
            ->select(
                'file_imports.id',
                'file_imports.file_name',
                'users.name',
                'file_imports.status',
                'file_imports.file_path'
            );
        return DataTables::of($proofs)
            ->addColumn('action', function ($proof) {
                if(file_exists(STUDENT_PATH_STORE.$proof->file_path)) {
                    $link = asset(STUDENT_PATH_STORE.$proof->file_path);
                    $linkViewFile = "<a href='$link'> $proof->file_name </a>";
                }
                elseif(file_exists(STUDENT_LIST_EACH_SEMESTER_PATH.$proof->file_path)) {
                    $link = asset(STUDENT_LIST_EACH_SEMESTER_PATH.$proof->file_path);
                    $linkViewFile = "<a href='$link'> $proof->file_name </a>";
                }
                else {
                    $link = '';
                    $linkViewFile = $proof->file_name;
                }
                $linkDownload = "<a title='Tải file' href='$link' target='_blank' class='btn btn-primary'>
                   <i class='fa fa-eye' aria-hidden='true'></i>$linkViewFile</a>";

                return "$linkDownload";

            })
            ->make(true);
    }

}
