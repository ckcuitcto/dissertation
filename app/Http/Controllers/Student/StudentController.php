<?php

namespace App\Http\Controllers\Student;

use App\Model\Classes;
use App\Model\EvaluationForm;
use App\Model\Faculty;
use     App\Model\Role;
use App\Model\Semester;
use App\Model\Student;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;
use Validator;

//use Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::where('role_id', '<=', ROLE_BANCANSULOP)->get();

//        $users = DB::table('users')
//            ->leftJoin('roles','users.role_id','=','roles.id')
//            ->where('roles.weight', '<=', 2)
//            ->select('users.*')->get();

        return view('student.index', compact('users'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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

    //import students
    public function import(Request $request)
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

            $arrFile = $request->file('fileImport');
            foreach ($arrFile as $file) {
                if ($file->getClientOriginalExtension() != "xlsx") {
                    $arrMessage = array("fileImport" => ["File " . $file->getClientOriginalName() . " không hợp lệ "]);
                    return response()->json([
                        'status' => false,
                        'arrMessages' => $arrMessage
                    ], 200);
                }
            }
            foreach ($arrFile as $file) {
                $fileName = str_random(8) . "_" . $file->getClientOriginalName();
                while (File::exists("upload/student/" . $fileName)) {
                    $fileName = str_random(8) . "_" . $file->getClientOriginalName();
                }
                $file->move('upload/student/', $fileName);

                $arrUser = array();
                $arrUpdateStudent = array();
                $arrKey = array();
                $arrError = array();
                $dataFileExcel = \Maatwebsite\Excel\Facades\Excel::load("upload/student/" . $fileName, function ($reader) {
                })->get();
                foreach ($dataFileExcel as $key => $value) {
                    //nếu cả tất cả giá trị đều null. thì bỏ qua.
                    if (!(empty($value->mssv) AND empty($value->khoa) AND empty($value->lop) AND empty($value->nien_khoa) AND empty($value->ho) AND empty($value->ten))) {
                        // nếu 1 trong các giá trị null. thì báo lỗi
                        if (!(empty($value->mssv) OR empty($value->khoa) OR empty($value->lop) OR empty($value->nien_khoa) OR empty($value->ho) OR empty($value->ten))) {

                            if (!empty($facultyId)) {
                                if ($value->khoa != $facultyId->name) {
                                    $facultyId = Faculty::where('name', 'like', "%$value->khoa%")->first();
                                }
                            } else {
                                $facultyId = Faculty::where('name', 'like', "%$value->khoa%")->first();
                            }
                            if (empty($facultyId)) {
                                $arrError[] = "Khoa " . $value->khoa . " không tồn tại";
                            }

                            if (!empty($classId)) {
                                if ($value->lop != $classId->name) {
                                    $classId = Classes::where('name', 'like', "%$value->lop%")->first();
                                }
                            } else {
                                $classId = Classes::where('name', 'like', "%$value->lop%")->first();
                            }
                            if (empty($classId)) {
                                $arrError[] = "Lớp " . $value->lop . " không tồn tại";
                            }

                            if (!empty($classId) AND !empty($facultyId)) {
                                $academicYearFrom = trim(explode('-', $value->nien_khoa)[0]);
                                $academicYearTo = trim(explode('-', $value->nien_khoa)[1]);

                                $arrUser[] = [
                                    'id' => $value->mssv,
                                    'name' => $value->ho . ' ' . $value->ten,
                                    'password' => bcrypt($value->mssv),
                                    'role_id' => ROLE_SINHVIEN,
                                    'faculty_id' => $facultyId->id
                                ];
                                $arrKey [] = [
                                    'user_id' => $value->mssv
                                ];

                                $arrUpdateStudent[] = [
                                    'class_id' => $classId->id,
                                    'academic_year_from' => $academicYearFrom,
                                    'academic_year_to' => $academicYearTo
                                ];
                            }
                        } else {
//                            var_dump($value);
                            $arrError[] = "Lỗi giá trị ở dòng có STT " . $value->stt;
                        }
                    }
                }
                // create user
//                var_dump($arrError);
//                die;
                if (empty($arrError)) {
                    if (!empty($arrUser)) {
                        User::insert($arrUser);
                        for ($i = 0; $i < count($arrKey); $i++) {
                            Student::updateOrCreate(
                                $arrKey[$i], $arrUpdateStudent[$i]
                            );
                        }
                        $this->addEvaluationFormAfterInportStudent($arrKey, $arrUpdateStudent);
                        return response()->json([
                            'status' => true
                        ], 200);
                    }else{
                        $arrError[] = [
                            'error' => 'File Lỗi'
                        ];
                        return response()->json([
                            'status' => false,
                            'errors' =>$arrError
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'errors' => $arrError
                    ], 200);
                }

            }
        }
    }

    private function addEvaluationFormAfterInportStudent($arrKey = array(),$arrStudent = array())
    {

        $arrEvaluationForm = array();
        for ($i = 0; $i < count($arrKey); $i++) {
            // lấy ra id sinh viên
            $student = Student::where('user_id','=',$arrKey[$i]['user_id'])->select('id')->first();

            // vào từng sinh viên. lấy ra danh sách học kì của sinh viên đó.
            //ví dụ sinh viên khóa 2014- 2018. thì vào bảng học kì. lấy học kì nào có year_from >= 2014 và year_to <= 2018.lấy ra tất cả học kì trong khoảng đó.
            // sau khi có học kì sẽ thêm từng form vào học kì

            //để tránh sinh viên nào cũng phải truy vấn sql. thì kiểm tra xem khóa của sinh viên trước và sinh viên sau
            // nếu giống thì lấy của sinh viên trước luôn. khỏi truy vấn
            if ($i != 0) {
                if (!($arrStudent[$i]['academic_year_from'] == $arrStudent[$i - 1]['academic_year_from'] AND $arrStudent[$i]['academic_year_to'] == $arrStudent[$i - 1]['academic_year_to'])) {
                    $semesters = Semester::where('year_from', '>=', $arrStudent[$i]['academic_year_from'])
                        ->where('year_to', '<=', $arrStudent[$i]['academic_year_to'])->get();
                }
            } else {
                $semesters = Semester::where([
                    ['year_from', '>=', $arrStudent[$i]['academic_year_from']],
                    ['year_to', '<=', $arrStudent[$i]['academic_year_to']]
                ])->get();
            }

            foreach ($semesters as $value) {
                $arrEvaluationForm[] = [
                    'semester_id' => $value->id,
                    'student_id' => $student->id
                ];
            }
        }
        EvaluationForm::insert($arrEvaluationForm);
    }
}
