<?php

namespace App\Http\Controllers\Export;

use App\Model\AcademicTranscript;
use App\Model\Classes;
use App\Model\Discipline;
use App\Model\Faculty;
use App\Model\FileImport;
use App\Model\Role;
use App\Model\Semester;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Yajra\DataTables\DataTables;

class ExportController extends Controller
{

    public function index()
    {
        $userLogin = $this->getUserLogin();

        $currentSemester = $this->getCurrentSemester();
        $semesters = Semester::select('id', DB::raw("CONCAT('Học kì: ',term,'*** Năm học: ',year_from,' - ',year_to) as value"))->get()->toArray();

        if ($userLogin->Role->weight == ROLE_PHONGCONGTACSINHVIEN OR $userLogin->Role->weight == ROLE_ADMIN) {
            $faculties = Faculty::all()->toArray();
            $faculties = array_prepend($faculties, array('id' => 0, 'name' => 'Tất cả khoa'));
        } else {
            $faculties = Faculty::where('id', $userLogin->Faculty->id)->get()->toArray();
        }

        return view('export.index', compact('faculties', 'semesters', 'currentSemester'));
    }

    public function ajaxGetClasses(Request $request)
    {
        $user = $this->getUserLogin();
        if($user->Role->weight >= ROLE_PHONGCONGTACSINHVIEN) {
            $classes = DB::table('student_list_each_semesters')
                ->leftJoin('students', 'students.user_id', '=', 'student_list_each_semesters.user_id')
                ->leftJoin('classes', 'classes.id', '=', 'student_list_each_semesters.class_id')
                ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
                ->select(
                    DB::raw('count(student_list_each_semesters.user_id) AS count'),
                    'classes.*'
                )
                ->groupBy('student_list_each_semesters.class_id');
        }elseif($user->Role->weight == ROLE_BANCHUNHIEMKHOA) {
            $classes = DB::table('student_list_each_semesters')
                ->leftJoin('students', 'students.user_id', '=', 'student_list_each_semesters.user_id')
                ->leftJoin('classes', 'classes.id', '=', 'student_list_each_semesters.class_id')
                ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
                ->select(
                    DB::raw('count(student_list_each_semesters.user_id) AS count'),
                    'classes.*'
                )
                ->where('classes.faculty_id',$user->faculty_id)
                ->groupBy('student_list_each_semesters.class_id');
        }elseif($user->Role->weight == ROLE_COVANHOCTAP) {
            $classes = DB::table('student_list_each_semesters')
                ->leftJoin('students', 'students.user_id', '=', 'student_list_each_semesters.user_id')
                ->leftJoin('classes', 'classes.id', '=', 'student_list_each_semesters.class_id')
                ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
                ->select(
                    DB::raw('count(student_list_each_semesters.user_id) AS count'),
                    'classes.*'
                )
                ->where('student_list_each_semesters.staff_id',$user->Staff->id)
                ->groupBy('student_list_each_semesters.class_id');
        }

        $dataTables = DataTables::of($classes)
            ->addColumn('action', function ($class) {
                $checkBox = "<div class='animated-checkbox'> <label><input type='checkbox' name='classes[]' class='checkboxClasses' value='$class->id'><span class='label-text'></span></label></div>";
                return $checkBox;
            })
            ->filter(function ($class) use ($request) {
                $faculty = $request->has('faculty_id');
                $facultyValue = $request->get('faculty_id');

                if (!empty($faculty) AND $facultyValue != 0) {
                    $class->where('users.faculty_id', '=', $facultyValue);
                }

                $semester = $request->has('semester_id');
                $semesterValue = $request->get('semester_id');
                if (!empty($semester) AND $semesterValue != 0) {
                    $class->where('student_list_each_semesters.semester_id', '=', $semesterValue);
                }
            });
        return $dataTables->make(true);
    }

    public function exportVer2(Request $request)
    {

        if (!empty($request->classes)) {
            $semesterId = $request->semesterChoose;

            $classes = Classes::whereIn('id', $request->classes)->get();
            $arrFileName = array();
            foreach ($classes as $key => $class) {
                $className2 = $class->name;
                $className1 = str_replace('-', '_', $class->name);
                $className3 = str_replace('_', '-', $class->name);

                $fileImport = FileImport::where('semester_id', $semesterId)
                    ->where('file_name', 'like', "%$className2%")
                    ->orWhere('file_name', 'like', "%$className1%")
                    ->orWhere('file_name', 'like', "%$className3%")
                    ->first();
                $dataFileExcel = Excel::load(STUDENT_LIST_EACH_SEMESTER_PATH . $fileImport->file_path, function ($reader) {
                })->noHeading()->get();

                $arrScoreByFile = array(); // mảng này lưu tất cả điểm của sinh viên trong 1 lớp

                // = no: nghĩa là export theo điểm sv chấm
                // yes là điểm đã sửa trong bảg điểm, có kỉ luật
                if($request->withDiscipline == 'no') {
                    for ($i = 10; $i < count($dataFileExcel); $i++) {
                        if (!empty($dataFileExcel[$i][0]) AND !empty($dataFileExcel[$i][1])) {
                            $arrScore = array();
                            $userId = $dataFileExcel[$i][1];
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
                                $totalScore = $resultLevel1[0]->total;
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
                                $arrScoreTmp[] = $totalScore;
                                $arrScoreTmp[] = $this->checkRank1($totalScore);
                                $arrScoreTmp[] = '';
                                $arrScore = $arrScoreTmp;
                            }

                            if (empty($arrScore)) {
                                $arrScore = array('', '', '', '', '', '', '', 0, $this->checkRank1(0), '*');
                            }
                            $arrScoreByFile[$userId] = $arrScore;
                            //nếu k có điểm
                        }
                    }
                } else {
                    for ($i = 10; $i < count($dataFileExcel); $i++) {
                        if (!empty($dataFileExcel[$i][0]) AND !empty($dataFileExcel[$i][1])) {
                            $userId = $dataFileExcel[$i][1];
                            $students = DB::table('academic_transcripts')
                                ->leftJoin('classes', 'classes.id', '=', 'academic_transcripts.class_id')
                                ->leftJoin('students', 'students.user_id', '=', 'academic_transcripts.user_id')
                                ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
                                ->leftJoin('faculties', 'faculties.id', '=', 'users.faculty_id')
                                ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                                ->where('academic_transcripts.user_id', $userId)
                                ->where('academic_transcripts.semester_id', $semesterId)
                                ->select(
                                    'academic_transcripts.score_ia',
                                    'academic_transcripts.score_ib',
                                    'academic_transcripts.score_ic',
                                    'academic_transcripts.score_ii',
                                    'academic_transcripts.score_iii',
                                    'academic_transcripts.score_iv',
                                    'academic_transcripts.score_v',
                                    DB::raw("
                                    academic_transcripts.score_i +
                                    academic_transcripts.score_ii +
                                    academic_transcripts.score_iii +
                                    academic_transcripts.score_iv +
                                    academic_transcripts.score_v
                                    as totalScore"),
                                    'academic_transcripts.note'
                                )->first();

                            $arrScoreTmp = (array)$students;
                            $arrScoreTmp[] = $this->checkRank1($arrScoreTmp['totalScore']);
                            $arrScoreTmp['notes'] = $arrScoreTmp['note'];
                            unset($arrScoreTmp['note']);

                            if($arrScoreTmp['totalScore'] > 100){
                                $arrScoreTmp['totalScore'] = 100;
                            }

                            if (!$this->checkIfEmptyScore($arrScoreTmp)) {
                                $arrScoreTmp = array('', '', '', '', '', '', '', 0, $this->checkRank1(0), '*');
                            }
                            $arrScoreByFile[$userId] = $arrScoreTmp;
                            //nếu k có điểm
                        }
                    }
                }
                //mở file và sửa file, sau đó lưu thahf file mới
                $arrColumns = array('F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
                Excel::load(STUDENT_LIST_EACH_SEMESTER_PATH . $fileImport->file_path, function ($reader) use ($arrScoreByFile, $arrColumns,$dataFileExcel) {
                    $sheet = $reader->getSheet(0);
                    for ($i = 0; $i < count($arrScoreByFile); $i++) {
                        $row = $i + 11;
                        $arrScore = $arrScoreByFile[$sheet->getCell('B' . $row)->getValue()];
                        $arrScore = array_values($arrScore);
                        foreach ($arrColumns as $key => $cl) {
                            $sheet->setCellValue($cl . $row, $arrScore[$key]);
                        }
                    }
                    //set lại ngày tháng
                    // vì các file đặt lộn xộn k đúng vị trí ngày tháng
                    // nên kiểm tra ở row 3. nếu có giá trị thì set lại ngày
                    if(!empty($dataFileExcel[2][6])){
                        $sheet->setCellValue("G3", $this->getCurrentDate());
                    }elseif(!empty($dataFileExcel[2][8])){
                        $sheet->setCellValue("I3", $this->getCurrentDate());
                    }

                })->store('xlsx', STUDENT_PATH, true);
                $arrFileName[] = $fileImport->file_name;
            }
        }

//        $arrFileName = $this->convertXLSXtoXLS($arrFileName);
        if (!empty($arrFileName)) {
//            $public_dir = dirname(dirname(public_path()));
            $public_dir = public_path();
            $zip = new ZipArchive();
            $fileZipName = "danh_sach" . Carbon::now()->format('dmY') . ".zip";
            foreach ($arrFileName as $file) {
                if ($zip->open($public_dir . '/' . STUDENT_PATH . $fileZipName, ZipArchive::CREATE) === TRUE) {
                    $zip->addFile(STUDENT_PATH . $file,$file);
                }
            }
            $zip->close();
            $headers = array(
                'Content-Type' => 'application/zip',
            );
            $fileToPath = $public_dir . '/' . STUDENT_PATH . $fileZipName;
            if (file_exists($fileToPath)) {
                foreach ($arrFileName as $file) {
                    unlink($public_dir . '/' . STUDENT_PATH . $file);
                }
//                return response()->download($fileToPath, $fileZipName, $headers)->deleteFileAfterSend(true);
//                return response()->download($fileToPath)->deleteFileAfterSend(true);
                return response()->file($fileToPath,$headers);
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    private function getCurrentDate(){
        $day= Carbon::now()->format('d');
        $month= Carbon::now()->format('m');
        $year= Carbon::now()->format('Y');
        return "Tp. Hồ Chí Minh, ngày $day tháng $month năm $year";
    }

    private function convertXLSXtoXLS($arrFileName){
        $arrTmp = array();
        foreach($arrFileName as $value){
            $arrName = explode('.',$value);
            array_pop($arrName);
            $arrTmp[] = implode($arrName).".xls";
        }
        return $arrTmp;
    }

    // EXPORT DANH SÁCH USER VS ĐIỂM CHẤM CHƯA CÓ KỈ LUẬT
    public function exportByUserId(Request $request)
    {

        $strUserId = $request->strUsersId;
        $strUserName = $request->strUserName;
        $strClassName = $request->strClassName;
        $semesterId = $request->semesterChoose;
        $facultyId = $request->facultyChoose;

        $arrUserId = explode(',', $strUserId);
        $arrUserName = explode(',', $strUserName);
        $arrClassName = explode(',', $strClassName);

        $arrScoreAllUser = array(); // mảng này lưu tất cả điểm của sinh viên
        for ($i = 0; $i < count($arrUserId); $i++) {
            $userId = $arrUserId[$i];
            $arrScore = array(); // tạo mảng mới. để nếu sinh viên sau chưa chấm. sẽ k bị gán giá trị của sv trước
            // tọa mảng với mssv, tên ,lớp
            // mảng tách ra từ userName
            $arrayUserName = explode(' ', $arrUserName[$i]);
            $lastName = array_pop($arrayUserName); //lấy ra phần tử cuối cùng là tên và tách nó khỏi mảng
            $firstName = implode(' ',$arrayUserName);
            $arrUserInfo = array($userId, $firstName,$lastName,$arrClassName[$i]);

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
                $totalScore = $resultLevel1[0]->total;
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
                $arrScoreTmp[] = $totalScore;
                $arrScoreTmp[] = $this->checkRank1($totalScore);
                $arrScoreTmp[] = '';

                $arrScoreTmp = array_merge($arrUserInfo,$arrScoreTmp);
                $arrScore = $arrScoreTmp;
            }

            if (empty($arrScore)) {
                $arrScore = array('', '', '', '', '', '', '', 0, $this->checkRank1(0), '*');
                $arrScore = array_merge($arrUserInfo,$arrScore);
            }
            $arrScoreAllUser[] = $arrScore;
        }

        $semester = Semester::find($semesterId);
        if($facultyId == 0){
            $facultyName = " Tất cả khoa";
        }else{
            $facultyName = Faculty::find($facultyId)->name;
        }
        //mở file và sửa file, sau đó lưu thanh file mới
        $arrColumns = array('A','B','C','D','E','F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
        ob_end_clean();

        ob_start(); //At the very top of your program (first line)
        Excel::load(FILE_TEMPLATE . FILE_TONG_HOP_DANH_GIA_REN_LUYEN_XLS, function ($reader) use ($arrScoreAllUser,$arrColumns,$semester,$facultyName) {
//            sheet 0 là lớp. sheet 1 là khoa
//            $sheet = $reader->getSheet(1);
            $reader->sheet('khoa', function ($sheet) use ($arrScoreAllUser,$arrColumns,$semester,$facultyName) {
                for ($i = 0; $i < count($arrScoreAllUser); $i++) {
                    $row = $i + 14;
                    $rowValue = array_merge(array($i+1),$arrScoreAllUser[$i]);
                    $sheet->row($row,$rowValue);

                    $range = "A$row:B$row";
                    $sheet->setBorder($range, 'thin');
                    $sheet->cells($range, function ($cells) {
                        $cells->setFont(array(
                            'size' => '10',
                            'bold' => false,
                        ));
                        $cells->setAlignment('center');
                    });

                    $range = "E$row:O$row";
                    $sheet->setBorder($range, 'thin');
                    $sheet->cells($range, function ($cells) {
                        $cells->setFont(array(
                            'size' => '10',
                            'bold' => false,
                        ));
                        $cells->setAlignment('center');
                    });

                    $range = "C$row:D$row";
                    $sheet->setBorder($range, 'thin');
                    $sheet->cells($range, function ($cells) {
                        $cells->setFont(array(
                            'size' => '10',
                            'bold' => false,
                        ));
                        $cells->setAlignment('left');
                    });
                }


                $sheet->cell('J5', function ($cell){
                    $day = date("d");
                    $month = date("m");
                    $year = date("Y");
                    $cell->setValue("Tp. Hồ Chí Minh, ngày $day tháng $month năm $year ");
                });

                $sheet->cell('E8', function ($cell) use ($facultyName){
                    $cell->setValue("Khoa: $facultyName");
                });
                $sheet->cell('F9', function ($cell) use ($semester){
                    $cell->setValue("Học kỳ: $semester->term");
                });
                $sheet->cell('I9', function ($cell) use ($semester){
                    $cell->setValue("Năm học: $semester->year_from - $semester->year_to ");
                });

                // xác định row có phần chữ kĩ = số User + 14 + 2(2 dòng khoảng cách ra)
                $rowSign = count($arrScoreAllUser) + 14 + 1;

                $sheet->mergeCells("A$rowSign:G$rowSign");
                $sheet->cells("A$rowSign:G$rowSign", function ($cells) {
                    $cells->setFont(array(
                        'size' => '11',
                        'bold' => true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell("A$rowSign", function ($cell) {
                    $cell->setValue("TM. HỘI ĐỒNG CẤP KHOA");
                });

                $sheet->mergeCells("K$rowSign:O$rowSign");
                $sheet->cells("K$rowSign:O$rowSign", function ($cells) {
                    $cells->setFont(array(
                        'size' => '11',
                        'bold' => true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell("K$rowSign", function ($cell) {
                    $cell->setValue("Người lập bảng");
                });

                $rowSign += 1;
                $sheet->mergeCells("A$rowSign:G$rowSign");
                $sheet->cells("A$rowSign:G$rowSign", function ($cells) {
                    $cells->setFont(array(
                        'size' => '11',
                        'bold' => true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell("A$rowSign", function ($cell) {
                    $cell->setValue("Chủ tịch");
                });

            });
        })->store('xls', STUDENT_PATH, true);
//        $public_dir = dirname(dirname(public_path()));
        $public_dir = public_path();
        $headers = array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => "attachment; filename='Report.xls'",
            'Content-Transfer-Encoding' => "binary",
            'Accept-Ranges' => "bytes",
        );
        $fileToPath = $public_dir . '/' . STUDENT_PATH . FILE_TONG_HOP_DANH_GIA_REN_LUYEN_XLS;
        if (file_exists($fileToPath)) {
//            return response()->download($fileToPath, FILE_TONG_HOP_DANH_GIA_REN_LUYEN, $headers)->deleteFileAfterSend(true);
//            return response()->download($fileToPath)->deleteFileAfterSend(true);
            return response()->file($fileToPath,$headers);
        } else {
            return redirect()->back();
        }
    }

    public function export(Request $request)
    {
        if (!empty($request->classes)) {
            $classes = Classes::whereIn('id', $request->classes)->get();
            $arrFileName = array();
            foreach ($classes as $key => $class) {
                Excel::create($class->name, function ($excel) use ($class) {
                    $excel->sheet('Sheet1', function ($sheet) use ($class) {
                        $sheet->setWidth(array(
                            'A' => 10,
                            'B' => 15,
                            'C' => 30,
                            'E' => 10,
                        ));
                        //SET FONT
                        $sheet->setStyle(array(
                            'font' => array(
                                'name' => 'Times New Roman'
                            )
                        ));
                        $sheet->mergeCells('A1:F1');
                        $sheet->cells('A1:F1', function ($cells) {
                            $cells->setFont(array(
                                'size' => '12',
                                'align' => 'center'
                            ));
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('G1:O1');
                        $sheet->cells('G1:O1', function ($cells) {
                            $cells->setFont(array(
                                'size' => '12',
                                'bold' => true,
                            ));
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('A2:F2');
                        $sheet->cells('A2:F2', function ($cells) {
                            $cells->setFont(array(
                                'size' => '12',
                                'bold' => true,
                            ));
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('G2:O2');
                        $sheet->cells('G2:O2', function ($cells) {
                            $cells->setFont(array(
                                'size' => '12',
                                'bold' => true,
                                'underline' => true
                            ));
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('G3:O3');
                        $sheet->cells('G3:O3', function ($cells) {
                            $cells->setFont(array(
                                'size' => '12',
                                'italic' => true,
                            ));
                            $cells->setAlignment('right');
                        });
                        $sheet->mergeCells('A4:O4');
                        $sheet->cells('A4:O4', function ($cells) {
                            $cells->setFont(array(
                                'size' => '14',
                                'bold' => true,
                            ));
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('C5:E5');
                        $sheet->cells('C5:E5', function ($cells) {
                            $cells->setFont(array(
                                'size' => '12',
                                'bold' => true,
                            ));
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('F5:M5');
                        $sheet->cells('F5:M5', function ($cells) {
                            $cells->setFont(array(
                                'size' => '12',
                                'bold' => true,
                            ));
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('C6:E6');
                        $sheet->cells('C6:E6', function ($cells) {
                            $cells->setFont(array(
                                'size' => '12',
                                'bold' => true,
                            ));
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('F6:M6');
                        $sheet->cells('F6:M6', function ($cells) {
                            $cells->setFont(array(
                                'size' => '12',
                                'bold' => true,
                            ));
                            $cells->setAlignment('center');
                        });
                        $sheet->mergeCells('A8:A9');
                        $sheet->mergeCells('B8:B9');
                        $sheet->setMergeColumn(array(
                            'columns' => array('C', 'D'),
                            'rows' => array(
                                array(8, 9)
                            )
                        ));

                        $sheet->mergeCells('E8:E9');
                        $sheet->mergeCells('F8:H8');
                        $sheet->mergeCells('I8:I9');
                        $sheet->mergeCells('J8:J9');
                        $sheet->mergeCells('K8:K9');
                        $sheet->mergeCells('L8:L9');
                        $sheet->mergeCells('M8:M9');
                        $sheet->mergeCells('N8:N9');
                        $sheet->mergeCells('O8:O9');
                        $sheet->mergeCells('C10:D10');

                        $sheet->setBorder('A8:O20', 'thin');

                        // SET VALUE
                        $sheet->cell('A1', function ($cell) {
                            $cell->setValue('BỘ GIÁO DỤC VÀ ĐÀO TẠO');
                        });
                        $sheet->cell('G1', function ($cell) {
                            $cell->setValue('CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM');
                        });
                        $sheet->cell('A2', function ($cell) {
                            $cell->setValue('TRƯỜNG ĐH CÔNG NGHỆ SÀI GÒN');
                        });
                        $sheet->cell('G2', function ($cell) {
                            $cell->setValue('Độc lập - Tự do - Hạnh phúc');
                        });
                        $sheet->cell('G3', function ($cell) {
                            $cell->setValue('Tp. Hồ Chí Minh, ngày  xx   tháng 6 năm 2017');
                        });
                        $sheet->cell('A4', function ($cell) {
                            $cell->setValue('BẢNG TỔNG HỢP ĐÁNH GIÁ KẾT QUẢ RÈN LUYỆN CỦA SINH VIÊN');
                        });
                        $sheet->cell('C5', function ($cell) use ($class) {
                            $cell->setValue('Lớp: ' . $class->name);
                        });
                        $sheet->cell('F5', function ($cell) use ($class) {
                            $cell->setValue('Khoa: ' . $class->Faculty->name);
                        });
                        $sheet->cell('C6', function ($cell) {
                            $cell->setValue('Học kỳ: II');
                        });
                        $sheet->cell('F6', function ($cell) {
                            $cell->setValue('Năm học: 2017 - 2018');
                        });

                        $arrHeader[] = array(
                            'A8' => 'Stt',
                            'B8' => 'MSSV',
                            'C8' => 'Họ và tên',
                            'E8' => 'Lớp',
                            'F8' => 'I',
                            'I8' => 'II',
                            'J8' => 'III',
                            'K8' => 'IV',
                            'L8' => 'V',
                            'M8' => 'Tổng điểm',
                            'N8' => 'Xếp loại',
                            'O8' => 'Ghi chú',
                        );
                        $arrHeader[] = array(
                            'F9' => 'a',
                            'G9' => 'b',
                            'H9' => 'c',
                        );
                        foreach ($arrHeader as $heading) {
                            foreach ($heading as $key => $value) {
                                $sheet->cell($key, function ($cell) use ($value) {
                                    $cell->setValue($value);
                                    $cell->setFont(array(
                                        'size' => '12',
                                        'bold' => true,
                                    ));
                                    $cell->setAlignment('center');
                                    $cell->setBackground('#CCCCCC');
                                    $cell->setValignment('center');
                                });
                            }
                        }
                        // cột này hiện tại chưa merge đc nên phải set riểng( d8);
                        $sheet->cell('D8', function ($cell) {
                            $cell->setBackground('#CCCCCC');
                        });
                        for ($i = 1; $i <= 15; $i++) {
                            if ($i < 4) {
                                $arrTmp[] = "($i)";
                            } elseif ($i > 4) {
                                $val = $i - 1;
                                $arrTmp[] = "($val)";
                            } else {
                                $arrTmp[] = "";

                            }
                        }
                        $sheet->row(10, $arrTmp);
                        $sheet->row(10, function ($row) {
                            $row->setFont(array(
                                'size' => '12',
                                'italic' => true,
                            ));
                            $row->setAlignment('center');
                            $row->setBackground('#CCCCCC');
                            $row->setValignment('center');
                        });
                    });
                    // Set sheets
                })->store('xlsx', 'exports/', true);
                $arrFileName[] = $class->name . '.xlsx';
            }
        }

        if (!empty($arrFileName)) {
//            $public_dir = dirname(dirname(public_path()));
            $public_dir = public_path();
            $zip = new ZipArchive();
            $fileZipName = "danh_sach" . Carbon::now()->format('dmY') . ".zip";
            foreach ($arrFileName as $file) {
                if ($zip->open($public_dir . '/exports/' . $fileZipName, ZipArchive::CREATE) === TRUE) {
                    $zip->addFile('exports/' . $file);
                }
            }
            $zip->close();
            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );
            $fileToPath = $public_dir . '/exports/' . $fileZipName;
            if (file_exists($fileToPath)) {
                foreach ($arrFileName as $file) {
                    unlink($public_dir . '/exports/' . $file);
                }
                return response()->download($fileToPath, $fileZipName, $headers)->deleteFileAfterSend(true);
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function backup()
    {
        $facultisds = Faculty::all();

        $i = 1;
        $arrUser = array();
        foreach ($facultisds as $key => $val){
            if($i < 99999){

            }
        }

        $userLogin = $this->getUserLogin();

        $currentSemester = $this->getCurrentSemester();
        $semesters = Semester::select('id', DB::raw("CONCAT('Học kì: ',term,'*** Năm học: ',year_from,' - ',year_to) as value"))->get()->toArray();

        $rolesForSelectSearch = Role::all()->toArray();
        $rolesForSelectSearch = array_prepend($rolesForSelectSearch,array('id' => 0,'display_name' => 'Tất cả Role'));

        if ($userLogin->Role->weight == ROLE_PHONGCONGTACSINHVIEN OR $userLogin->Role->weight == ROLE_ADMIN) {
            $faculties = Faculty::all()->toArray();
            $faculties = array_prepend($faculties, array('id' => 0, 'name' => 'Tất cả khoa'));
        } else {
            $faculties = Faculty::where('id', $userLogin->Faculty->id)->get()->toArray();
        }

        return view('backup.index', compact('faculties', 'semesters', 'currentSemester','rolesForSelectSearch'));
    }

    public function ajaxGetUsers(Request $request){
        $users = DB::table('users')
            ->leftJoin('students','students.user_id','=','users.users_id')
            ->leftJoin('faculties','faculties.id','=','users.faculty_id')
            ->leftJoin('classes','classes.id','=','students.class_id')
            ->leftJoin('roles','roles.id','=','users.role_id')
            ->select(
                'users.*',
                'faculties.name as facultyName',
                'classes.name as className',
                'roles.display_name as roleName'
            );

        return DataTables::of($users)->filter(function ($student) use ($request) {
            $role = $request->has('role_id');
            $roleValue = $request->get('role_id');
            if (!empty($role) AND $roleValue != 0) {
                $student->where('roles.id', '=', $roleValue);
            }
            })->make(true);
    }

    public function ajaxGetEachSemester(Request $request){

        $user = $this->getUserLogin();
        $students = DB::table('student_list_each_semesters')
            ->leftJoin('classes', 'classes.id', '=', 'student_list_each_semesters.class_id')
            ->leftJoin('students', 'students.user_id', '=', 'student_list_each_semesters.user_id')
            ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
            ->leftJoin('faculties', 'faculties.id', '=', 'users.faculty_id')
            ->select(
                'users.users_id',
                'users.name as userName',
                'classes.name as className',
                'faculties.name as facultyName',
                DB::raw("CONCAT(students.academic_year_from,'-',students.academic_year_to) as academic"),
                'students.id',
                'students.id as studentId',
                'users.faculty_id',
                'students.class_id',
                'student_list_each_semesters.semester_id as semesterId',
                'student_list_each_semesters.monitor_id',
                'student_list_each_semesters.staff_id'
            );
        $dataTables = DataTables::of($students)
        ->addColumn('monitor', function ($student) {
            $monitor = DB::table('users')->where('users_id',$student->monitor_id)->select('name')->first();
            return $monitor->name;
        })
        ->addColumn('staffName', function ($student) {
            $staff = DB::table('users')->leftJoin('staff','users.users_id','=','staff.user_id')
                ->where('staff.id',$student->staff_id)->select('name')->first();
            return $staff->name;
        })
        ->addColumn('semesterInfo', function ($student) {
            $semester = DB::table('semesters')->where('id',$student->semesterId)->select(DB::raw("CONCAT('Học kì ',term,' năm học ',year_from,'-',year_to) as semesterInfo"))->first();
            return $semester->semesterInfo;
        })
        ->filter(function ($student) use ($request) {
            $faculty = $request->has('faculty_id');
            $facultyValue = $request->get('faculty_id');

            if (!empty($faculty) AND $facultyValue != 0) {
                $student->where('users.faculty_id', '=', $facultyValue);

            }

            $semester = $request->has('semester_id');
            $semesterValue = $request->get('semester_id');
            if (!empty($semester) AND $semesterValue != 0) {
                $student->where('student_list_each_semesters.semester_id', '=', $semesterValue);
            }
        });

        return $dataTables->make(true);
    }

    public function ajaxGetFaculties()
    {
        $faculties = DB::table('faculties')
            ->leftJoin('classes', 'classes.faculty_id', '=', 'faculties.id')
            ->select(
                'faculties.*',
                DB::raw('count(classes.faculty_id) AS countClass')
            )->groupBy('faculties.id');

        return DataTables::of($faculties)->make(true);
    }

    public function ajaxGetBackUpClass(Request $request)
    {
        $classes = DB::table('classes')
            ->leftJoin('students', 'classes.id', '=', 'students.class_id')
            ->select(
                'classes.*',
                DB::raw('count(students.id) AS countStudent')
            )->groupBy('classes.id');

        return DataTables::of($classes)
        ->filter(function ($class) use ($request) {
            $faculty = $request->has('faculty_id');
            $facultyValue = $request->get('faculty_id');

            if (!empty($faculty) AND $facultyValue != 0) {
                $class->where('classes.faculty_id', '=', $facultyValue);
            }
        })
        ->make(true);
    }

    public function ajaxGetBackUpSemester(Request $request)
    {
        $semesters = DB::table('semesters')
        ->select(
            'id',
            'term',
            DB::raw("CONCAT(year_from,'-',year_to) as year"),
            DB::raw("CONCAT(date_start,'-',date_end) as semesterDate"),
            DB::raw("CONCAT(date_start_to_mark,'-',date_end_to_mark) as date_mark"),
            DB::raw("CONCAT(date_start_to_request_re_mark,'-',date_end_to_request_re_mark) as date_request_re_mark"),
            DB::raw("CONCAT(date_start_to_re_mark,'-',date_start_to_re_mark) as date_re_mark")
        );

        $dataTable = DataTables::of($semesters)
            ->addColumn('detail', function ($semester) {
                $markTime = DB::table('mark_times')
                    ->leftJoin('roles','mark_times.role_id','=','roles.id')
                    ->where('semester_id',$semester->id)
                    ->select(
                        DB::raw("CONCAT(mark_time_start,' -> ',mark_time_end) as markTime"),
                        'roles.display_name as roleDisplayName'
                    )->get();

                $strDetail = '';
                foreach ($markTime as $key => $time){
                    $strDetail .= ($key+1).": ". $time->roleDisplayName .': '.$time->markTime."-*-*-" ;
                }
                return $strDetail;
            });
        return $dataTable->make(true);
    }

    public function ajaxGetBackUpAcademicTranscript(Request $request)
    {
        $academicTranscript = DB::table('academic_transcripts')
            ->leftJoin('users','users.users_id','=','academic_transcripts.user_id')
            ->leftJoin('classes','classes.id','=','academic_transcripts.class_id')
            ->select(
            DB::raw("@curRow := ifnull(@curRow,0) + 1 as stt"),
            'users.users_id',
            'users.name as userName',
            'classes.name as className',
            'academic_transcripts.semester_id',
            'academic_transcripts.score_i',
            'academic_transcripts.score_ii',
            'academic_transcripts.score_iii',
            'academic_transcripts.score_iv',
            'academic_transcripts.score_v',
            DB::raw("
                academic_transcripts.score_i +
                academic_transcripts.score_ii +
                academic_transcripts.score_iii +
                academic_transcripts.score_iv +
                academic_transcripts.score_v
                as totalScore")
        )->orderBy('classes.id','DESC');

        $dataTable = DataTables::of($academicTranscript)
            ->addColumn('rank', function ($aca) {
                return $this->checkRank1($aca->totalScore);
            })
            ->addColumn('note', function ($aca) {
                $disciplines = Discipline::select('id')->where('user_id',$aca->users_id)->where('semester_id',$aca->semester_id)->get();
                $arrId = [];
                foreach($disciplines as $val){
                    $arrId[] = "[$val->id]";
                }
                return implode(',',$arrId);
            })
            ->filter(function ($aca) use ($request) {
                $faculty = $request->has('faculty_id');
                $facultyValue = $request->get('faculty_id');

                if (!empty($faculty) AND $facultyValue != 0) {
                    $aca->where('users.faculty_id', '=', $facultyValue);
                }

                $semester = $request->has('semester_id');
                $semesterValue = $request->get('semester_id');
                if (!empty($semester) AND $semesterValue != 0) {
                    $aca->where('academic_transcripts.semester_id', '=', $semesterValue);
                }
            });

        return $dataTable->make(true);
    }


    // EXPORT DANH SÁCH USER VS ĐIỂM CHẤM CÓ KỈ LUẬT // có Ia. ib ic
    public function exportAcademicTranscript(Request $request)
    {
        $strUserId = $request->strUsersId;
        $strUserName = $request->strUserName;
        $strClassName = $request->strClassName;
        $semesterId = $request->semesterChoose;
        $facultyId = $request->facultyChoose;

        $arrUserId = explode(',', $strUserId);
//        $arrUserName = explode(',', $strUserName);
//        $arrClassName = explode(',', $strClassName);

        $academicTranscript = DB::table('academic_transcripts')
            ->leftJoin('classes', 'classes.id', '=', 'academic_transcripts.class_id')
            ->leftJoin('students', 'students.user_id', '=', 'academic_transcripts.user_id')
            ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
            ->select(
                'users.users_id',
                DB::raw("REVERSE (SUBSTRING( REVERSE(users.name), LOCATE(' ',REVERSE(users.name)), LENGTH(users.name) - LOCATE(' ',REVERSE(users.name)) ) ) as lastName"),
                DB::raw("SUBSTRING_INDEX(users.name, ' ', -1) as firstName"),
                'classes.name as className',
                'academic_transcripts.score_ia',
                'academic_transcripts.score_ib',
                'academic_transcripts.score_ic',
                'academic_transcripts.score_ii',
                'academic_transcripts.score_iii',
                'academic_transcripts.score_iv',
                'academic_transcripts.score_v',
                DB::raw("
                academic_transcripts.score_i +
                academic_transcripts.score_ii +
                academic_transcripts.score_iii +
                academic_transcripts.score_iv +
                academic_transcripts.score_v
                as totalScore"),
                'academic_transcripts.note'
            )->whereIn('academic_transcripts.user_id',$arrUserId);
            if(!empty($semesterId)){
                $academicTranscript = $academicTranscript->where('academic_transcripts.semester_id',$semesterId);
            }
            $academicTranscript = $academicTranscript->orderBy('classes.id','ASC')
            ->get()->toArray();

        for ($i = 0; $i < count($academicTranscript); $i++) {

            $tmp = (array)$academicTranscript[$i];
            $tmp['rank'] = $this->checkRank1($tmp['totalScore']);
            $tmp['notes'] = $tmp['note'];
            unset($tmp['note']);

            if($tmp['totalScore'] > 100){
                $tmp['totalScore'] = 100;
            }

            $academicTranscript[$i] = $tmp;
        }
        if(!empty($semesterId)){
            $semester = Semester::find($semesterId);
        }else{
            $semester = null;
        }
        if($facultyId == 0){
            $facultyName = " Tất cả khoa";
        }else{
            $facultyName = Faculty::find($facultyId)->name;
        }
        //mở file và sửa file, sau đó lưu thanh file mới
        $arrColumns = array('A','B','C','D','E','F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
        ob_end_clean();
        ob_start(); //At the very top of your program (first line)

        Excel::load(FILE_TEMPLATE . FILE_TONG_HOP_DANH_GIA_REN_LUYEN_XLS, function ($reader) use ($academicTranscript,$arrColumns,$semester,$facultyName) {
//            sheet 0 là lớp. sheet 1 là khoa
////            $sheet = $reader->getSheet(1);
            $reader->sheet('khoa', function ($sheet) use ($academicTranscript,$arrColumns,$semester,$facultyName) {
                for ($i = 0; $i < count($academicTranscript); $i++) {
                    $row = $i + 14;
                    $rowValue = array_merge(array($i+1),$academicTranscript[$i]);
                    $sheet->row($row,$rowValue);

                    $range = "A$row:B$row";
                    $sheet->setBorder($range, 'thin');
                    $sheet->cells($range, function ($cells) {
                        $cells->setFont(array(
                            'size' => '10',
                            'bold' => false,
                        ));
                        $cells->setAlignment('center');
                    });

                    $range = "E$row:O$row";
                    $sheet->setBorder($range, 'thin');
                    $sheet->cells($range, function ($cells) {
                        $cells->setFont(array(
                            'size' => '10',
                            'bold' => false,
                        ));
                        $cells->setAlignment('center');
                    });

                    $range = "C$row:D$row";
                    $sheet->setBorder($range, 'thin');
                    $sheet->cells($range, function ($cells) {
                        $cells->setFont(array(
                            'size' => '10',
                            'bold' => false,
                        ));
                        $cells->setAlignment('left');
                    });
                }

                $sheet->cell('J5', function ($cell){
                    $day = date("d");
                    $month = date("m");
                    $year = date("Y");
                    $cell->setValue("Tp. Hồ Chí Minh, ngày $day tháng $month năm $year ");
                });

                $sheet->cell('E8', function ($cell) use ($facultyName){
                    $cell->setValue("Khoa: $facultyName");
                });

                if(!empty($semester)){
                    $sheet->cell('F9', function ($cell) use ($semester){
                        $cell->setValue("Học kỳ: $semester->term");
                    });
                    $sheet->cell('I9', function ($cell) use ($semester){
                        $cell->setValue("Năm học: $semester->year_from - $semester->year_to ");
                    });
                }

                // xác định row có phần chữ kĩ = số User + 14 + 2(2 dòng khoảng cách ra)
                $rowSign = count($academicTranscript) + 14 + 1;

                $sheet->mergeCells("A$rowSign:G$rowSign");
                $sheet->cells("A$rowSign:G$rowSign", function ($cells) {
                    $cells->setFont(array(
                        'size' => '11',
                        'bold' => true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell("A$rowSign", function ($cell) {
                    $cell->setValue("TM. HỘI ĐỒNG CẤP KHOA");
                });

                $sheet->mergeCells("K$rowSign:O$rowSign");
                $sheet->cells("K$rowSign:O$rowSign", function ($cells) {
                    $cells->setFont(array(
                        'size' => '11',
                        'bold' => true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell("K$rowSign", function ($cell) {
                    $cell->setValue("Người lập bảng");
                });

                $rowSign += 1;
                $sheet->mergeCells("A$rowSign:G$rowSign");
                $sheet->cells("A$rowSign:G$rowSign", function ($cells) {
                    $cells->setFont(array(
                        'size' => '11',
                        'bold' => true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell("A$rowSign", function ($cell) {
                    $cell->setValue("Chủ tịch");
                });

            });
        })->store('xls', STUDENT_PATH, true);
        // chuyển lên host thì dugnf cái trên. local thì dùng dưới
//        $public_dir = dirname(dirname(public_path()));
        $public_dir = public_path();
        $headers = array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => "attachment; filename='Report.xls'",
            'Content-Transfer-Encoding' => "binary",
            'Accept-Ranges' => "bytes",
        );
        $fileToPath = $public_dir . '/' . STUDENT_PATH . FILE_TONG_HOP_DANH_GIA_REN_LUYEN_XLS;
        if (file_exists($fileToPath)) {
//            return response()->download($fileToPath, FILE_TONG_HOP_DANH_GIA_REN_LUYEN, $headers)->deleteFileAfterSend(true);
//            return response()->download($fileToPath)->deleteFileAfterSend(true);
            return response()->file($fileToPath,$headers);
        } else {
            return redirect()->back();
        }
    }

    // EXPORT DANH SÁCH USER VS ĐIỂM CHẤM CÓ KỈ LUẬT // CHỈ CÓ CÁC MỤC LỚN LEVEL 1
    public function exportAcademicTranscriptLevel1(Request $request)
    {
        $strUserId = $request->strUsersId;
        $strUserName = $request->strUserName;
        $strClassName = $request->strClassName;
        $semesterId = $request->semesterChoose;
        $facultyId = $request->facultyChoose;

        $arrUserId = explode(',', $strUserId);
//        $arrUserName = explode(',', $strUserName);
//        $arrClassName = explode(',', $strClassName);

        $academicTranscript = DB::table('academic_transcripts')
            ->leftJoin('classes', 'classes.id', '=', 'academic_transcripts.class_id')
            ->leftJoin('students', 'students.user_id', '=', 'academic_transcripts.user_id')
            ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
            ->select(
                'users.users_id',
                DB::raw("REVERSE (SUBSTRING( REVERSE(users.name), LOCATE(' ',REVERSE(users.name)), LENGTH(users.name) - LOCATE(' ',REVERSE(users.name)) ) ) as lastName"),
                DB::raw("SUBSTRING_INDEX(users.name, ' ', -1) as firstName"),
                'classes.name as className',
                'academic_transcripts.score_i',
                'academic_transcripts.score_ii',
                'academic_transcripts.score_iii',
                'academic_transcripts.score_iv',
                'academic_transcripts.score_v',
                DB::raw("
                academic_transcripts.score_i +
                academic_transcripts.score_ii +
                academic_transcripts.score_iii +
                academic_transcripts.score_iv +
                academic_transcripts.score_v
                as totalScore"),
                'academic_transcripts.semester_id'
            )->whereIn('academic_transcripts.user_id',$arrUserId);
            if(!empty($semesterId)){
                $academicTranscript = $academicTranscript->where('academic_transcripts.semester_id',$semesterId);
            }
            $academicTranscript = $academicTranscript->orderBy('classes.id','ASC')
            ->get()->toArray();

        for ($i = 0; $i < count($academicTranscript); $i++) {

            $tmp = (array)$academicTranscript[$i];
            $tmp['rank'] = $this->checkRank1($tmp['totalScore']);
            $tmp['notes'] = $this->getNoteByAcademicTranscript($tmp);
            unset($tmp['semester_id']);
            if($tmp['totalScore'] > 100){
                $tmp['totalScore'] = 100;
            }

            $academicTranscript[$i] = $tmp;
        }
        if(!empty($semesterId)){
            $semester = Semester::find($semesterId);
        }else{
            $semester = null;
        }
        if($facultyId == 0){
            $facultyName = " Tất cả khoa";
        }else{
            $facultyName = Faculty::find($facultyId)->name;
        }
        //mở file và sửa file, sau đó lưu thanh file mới
        $arrColumns = array('A','B','C','D','E','F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
        ob_end_clean();
        ob_start(); //At the very top of your program (first line)

        Excel::load(FILE_TEMPLATE . FILE_TONG_HOP_DANH_GIA_REN_LUYEN_LEVEL_1_XLS, function ($reader) use ($academicTranscript,$arrColumns,$semester,$facultyName) {
//            sheet 0 là lớp. sheet 1 là khoa
////            $sheet = $reader->getSheet(1);
            $reader->sheet('khoa', function ($sheet) use ($academicTranscript,$arrColumns,$semester,$facultyName) {
                for ($i = 0; $i < count($academicTranscript); $i++) {
                    $row = $i + 14;
                    $rowValue = array_merge(array($i+1),$academicTranscript[$i]);
                    $sheet->row($row,$rowValue);

                    $range = "A$row:B$row";
                    $sheet->setBorder($range, 'thin');
                    $sheet->cells($range, function ($cells) {
                        $cells->setFont(array(
                            'size' => '10',
                            'bold' => false,
                        ));
                        $cells->setAlignment('center');
                    });

                    $range = "E$row:M$row";
                    $sheet->setBorder($range, 'thin');
                    $sheet->cells($range, function ($cells) {
                        $cells->setFont(array(
                            'size' => '10',
                            'bold' => false,
                        ));
                        $cells->setAlignment('center');
                    });

                    $range = "C$row:D$row";
                    $sheet->setBorder($range, 'thin');
                    $sheet->cells($range, function ($cells) {
                        $cells->setFont(array(
                            'size' => '10',
                            'bold' => false,
                        ));
                        $cells->setAlignment('left');
                    });
                }

                $sheet->cell('J5', function ($cell){
                    $day = date("d");
                    $month = date("m");
                    $year = date("Y");
                    $cell->setValue("Tp. Hồ Chí Minh, ngày $day tháng $month năm $year ");
                });

                $sheet->cell('E8', function ($cell) use ($facultyName){
                    $cell->setValue("Khoa: $facultyName");
                });

                if(!empty($semester)){
                    $sheet->cell('F9', function ($cell) use ($semester){
                        $cell->setValue("Học kỳ: $semester->term");
                    });
                    $sheet->cell('I9', function ($cell) use ($semester){
                        $cell->setValue("Năm học: $semester->year_from - $semester->year_to ");
                    });
                }

                // xác định row có phần chữ kĩ = số User + 14 + 2(2 dòng khoảng cách ra)
                $rowSign = count($academicTranscript) + 14 + 1;

                $sheet->mergeCells("A$rowSign:G$rowSign");
                $sheet->cells("A$rowSign:G$rowSign", function ($cells) {
                    $cells->setFont(array(
                        'size' => '11',
                        'bold' => true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell("A$rowSign", function ($cell) {
                    $cell->setValue("TM. HỘI ĐỒNG CẤP KHOA");
                });

                $sheet->mergeCells("K$rowSign:O$rowSign");
                $sheet->cells("K$rowSign:O$rowSign", function ($cells) {
                    $cells->setFont(array(
                        'size' => '11',
                        'bold' => true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell("K$rowSign", function ($cell) {
                    $cell->setValue("Người lập bảng");
                });

                $rowSign += 1;
                $sheet->mergeCells("A$rowSign:G$rowSign");
                $sheet->cells("A$rowSign:G$rowSign", function ($cells) {
                    $cells->setFont(array(
                        'size' => '11',
                        'bold' => true,
                    ));
                    $cells->setAlignment('center');
                });
                $sheet->cell("A$rowSign", function ($cell) {
                    $cell->setValue("Chủ tịch");
                });
            });
        })->store('xls', STUDENT_PATH, true);
        // chuyển lên host thì dugnf cái trên. local thì dùng dưới
//        $public_dir = dirname(dirname(public_path()));
        $public_dir = public_path();
        $headers = array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => "attachment; filename='Report.xls'",
            'Content-Transfer-Encoding' => "binary",
            'Accept-Ranges' => "bytes",
        );
        $fileToPath = $public_dir . '/' . STUDENT_PATH . FILE_TONG_HOP_DANH_GIA_REN_LUYEN_LEVEL_1_XLS;
        if (file_exists($fileToPath)) {
//            return response()->download($fileToPath, FILE_TONG_HOP_DANH_GIA_REN_LUYEN, $headers)->deleteFileAfterSend(true);
//            return response()->download($fileToPath)->deleteFileAfterSend(true);
            return response()->file($fileToPath,$headers);
        } else {
            return redirect()->back();
        }
    }

    private function getNoteByAcademicTranscript($options){
        $disciplines = Discipline::select('id')->where('user_id',$options['users_id'])->where('semester_id',$options['semester_id'])->get();
        $arrId = [];
        foreach($disciplines as $val){
            $arrId[] = "[$val->id]";
        }
        return implode(',',$arrId);
    }

    private function checkIfEmptyScore($arrScore){
        foreach($arrScore as $val){
            if($val != 0) {
                return true;
            }
        }
        return false;
    }
}
