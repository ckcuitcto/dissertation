<?php

namespace App\Http\Controllers\Export;

use App\Model\Classes;
use App\Model\FileImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class ExportController extends Controller
{

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

    public function exportVer2(Request $request)
    {
        if (!empty($request->classes)) {
//            $semesterId = $request->semesterId;
            $semesterId = 2; // do chưa làm cái chọn học kì nên tạm thời để v. = 2

            $classes = Classes::whereIn('id', $request->classes)->get();
            $arrFileName = array();
            foreach ($classes as $key => $class) {
                $className2 = $class->name;
                $className1 = str_replace('-', '_', $class->name);

                $fileImport = FileImport::where('semester_id', $semesterId)
                    ->where('file_name', 'like', "%$className2%")
                    ->orWhere('file_name', 'like', "%$className1%")
                    ->first();
                $dataFileExcel = Excel::load(STUDENT_LIST_EACH_SEMESTER_PATH . $fileImport->file_path, function ($reader) {})->noHeading()->get();

                $arrScoreByFile = array(); // mảng này lưu tất cả điểm của sinh viên trong 1 lớp
                for ($i = 10; $i < count($dataFileExcel); $i++) {
                    if (!empty($dataFileExcel[$i][0]) AND !empty($dataFileExcel[$i][1])) {
                        $arrScore = array();
                        $userId = $dataFileExcel[$i][1];
//                        $userId = 'DH51400250';
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
                                ['evaluation_criterias.id', '<>',YTHUCTHAMGIAHOCTAP_ID]
                            ])
                            ->select(
                                'evaluation_results.marker_score',
                                'evaluation_results.marker_id',
                                'evaluation_forms.total'
                            )
                            ->orderBy('evaluation_results.created_at','DESC')
                            ->orderBy('evaluation_criterias.id','ASC')
                            ->limit(4)->get()->toArray();
                        if(!empty($resultLevel1)){
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
                                ->whereIn('evaluation_criterias.parent_id',EVALUATION_CRITERIAS_CHILD_PARENT_1)
                                ->select(
                                    DB::raw('SUM(evaluation_results.marker_score) as marker_score')
                                )
                                ->orderBy('evaluation_criterias.parent_id','ASC')
                                ->groupBy('evaluation_criterias.parent_id')
                                ->get()->toArray();

                            $arrScore = array_merge($resultLevel2,$resultLevel1);
                            $arrScoreTmp = array();
                            foreach($arrScore as $value){
                                $arrScoreTmp[] = $value->marker_score;
                            }
                            $arrScoreTmp[] = $totalScore;
                            $arrScoreTmp[] = $this->checkRank1($totalScore);
                            $arrScoreTmp[] ='';
                            $arrScore = $arrScoreTmp;
                        }

                        if(empty($arrScore)){
                            $arrScore = array('','','','','','','',0,$this->checkRank1(0),'*');
                        }
                        $arrScoreByFile[$userId] = $arrScore;
                        //nếu k có điểm
                    }

                }

                //mở file và sửa file, sau đó lưu thahf file mới
                $arrColumns = array('F','G','H','I','J','K','L','M','N','O');
                Excel::load(STUDENT_LIST_EACH_SEMESTER_PATH . $fileImport->file_path, function ($reader) use ($arrScoreByFile,$arrColumns) {
                    $sheet = $reader->getSheet(0);
//                    $sheet->setFormat(\PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
//                    $sheet->setColumnFormat(array(
////                        foreach($arrColumns as $key => $cl) {
//                            'A2:K2' => \PHPExcel_Style_NumberFormat::FORMAT_GENERAL;
////                        }
//                    ));

                    for($i=0; $i< count($arrScoreByFile); $i++) {
                        $row = $i + 11;
                        $arrScore = $arrScoreByFile[$sheet->getCell('B'.$row)->getValue()];

//                        var_dump($arrScore);
                        $arrFormat = array();
                        foreach($arrColumns as $key => $cl){
//                            echo $arrScore[$key]."-";
//                            echo $cl.$row.'------';
                            $sheet->setCellValue($cl.$row, $arrScore[$key]);
                        }
                    }
//                    $sheet->setColumnFormat($arrFormat);
                })->store('xlsx', STUDENT_PATH , true);
                $arrFileName[] = $fileImport->file_name;
            }
        }

        if (!empty($arrFileName)) {
            $public_dir = public_path();
            $zip = new ZipArchive();
            $fileZipName = "danh_sach" . Carbon::now()->format('dmY') . ".zip";
            foreach ($arrFileName as $file) {
                if ($zip->open($public_dir . '/'.STUDENT_PATH . $fileZipName, ZipArchive::CREATE) === TRUE) {
                    $zip->addFile(STUDENT_PATH . $file);
                }
            }
            $zip->close();
            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );
            $fileToPath = $public_dir . '/'.STUDENT_PATH. $fileZipName;
            if (file_exists($fileToPath)) {
                foreach ($arrFileName as $file) {
                    unlink($public_dir . '/'.STUDENT_PATH . $file);
                }
                return response()->download($fileToPath, $fileZipName, $headers)->deleteFileAfterSend(true);
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
