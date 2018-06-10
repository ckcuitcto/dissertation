<?php

namespace App\Http\Controllers\Student;

use App\Model\Classes;
use App\Model\EvaluationForm;
use App\Model\Faculty;
use App\Model\FileImport;
use     App\Model\Role;
use App\Model\Semester;
use App\Model\Student;
use App\Model\StudentListEachSemester;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Excel;
use Validator;
use Yajra\DataTables\DataTables;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $users = User::where('role_id', '<=', ROLE_BANCANSULOP)->paginate(25);
        $users = User::rightJoin('student_list_each_semesters','student_list_each_semesters.user_id','=','users.users_id')->select('users.*')->orderBy('student_list_each_semesters.id')->paginate(25);

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
        $student = DB::table('students')
            ->join('users', 'users.users_id', '=', 'students.user_id')
//            ->leftJoin('roles','roles.id','=','users.role_id')
            ->select('users.users_id', 'users.address', 'users.name', 'users.role_id', 'users.gender', 'users.users_id', 'students.status as studentStatus')->where('students.id', $id)->first();
        if (empty($student)) {
            return response()->json([
                'status' => false
            ], 200);
        }
        return response()->json([
            'student' => $student,
            'status' => true
        ], 200);
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
        $required['name'] = 'required';
        $required['studentStatus'] = 'required';

        $message['name.required'] = 'Vui lòng nhập tên';
        $message['studentStatus.required'] = 'Vui lòng chọn trạng thái';

        if ($request->changePassword == 'off') {
            $required['password'] = 'required|min:6';
            $required['rePassword'] = 'required|same:password';

            $message['password.required'] = 'Vui lòng nhập mật khẩu';
            $message['rePassword.required'] = 'Vui lòng nhập lại mật khẩu ';
            $message['rePassword.same'] = 'Mật khẩu không khớp ';
            $message['password.min'] = 'Mật khẩu phải có ít nhất 6 kí tự ';
        }
        $validator = Validator::make($request->all(), $required, $message);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'arrMessages' => $validator->errors()
            ], 200);
        } else {

            $user = User::where('users_id', $request->users_id)->first();
            if (!empty($user)) {
                $user->name = $request->name;
                $user->gender = $request->gender;
                $user->address = $request->address;
                $user->role_id = $request->role_id;
                $user->password = Hash::make($request->password);
                $user->save();

                Student::find($id)->update(['status' => $request->studentStatus]);

                return response()->json([
                    'status' => true
                ], 200);
            }
        }
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
            $arrFileName= array();
            foreach ($arrFile as $file) {

                config(['excel.import.startRow' => 1]);

                $fileName = $this->convert_vi_to_en(str_random(8) . "_" . $file->getClientOriginalName());
                while (File::exists(STUDENT_PATH . $fileName)) {
                    $fileName = $this->convert_vi_to_en(str_random(8) . "_" . $file->getClientOriginalName());
                }
                $file->move(STUDENT_PATH, $fileName);
                $arrFileName[] = $fileName;
                $arrUser = array();
                $arrUpdateStudent = array();
                $arrKey = array();
                $arrError = array();
                $dataFileExcel = \Maatwebsite\Excel\Facades\Excel::load(STUDENT_PATH . $fileName, function ($reader) {
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

                                if (!empty($value->lop_truong)) {
                                    $role = ROLE_BANCANSULOP;
                                } else {
                                    $role = ROLE_SINHVIEN;
                                }
                                $arrUser[] = [
                                    'users_id' => $value->mssv,
                                    'name' => $value->ho . ' ' . $value->ten,
                                    'password' => bcrypt($value->mssv),
                                    'role_id' => $role,
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
                            $arrError[] = "Lỗi giá trị ở dòng có STT " . $value->stt;
                        }
                    }
                }
            }
            $userLogin = Auth::user();
            if (empty($arrError)) {
                if (!empty($arrUser)) {
                    for($i = 0 ; $i< count($arrFile); $i++){
                        $arrFileImport[] = [
                            'file_path' => $arrFileName[$i],
                            'file_name' => $arrFile[$i]->getClientOriginalName(),
                            'status' => 'Thành công',
                            'staff_id' => $userLogin->Staff->id
                        ];
//                        $file->move(STUDENT_PATH, $fileName);
                        File::move(STUDENT_PATH.$arrFileName[$i],STUDENT_PATH_STORE.$arrFileName[$i]);
                    }
                    FileImport::insert($arrFileImport);

                    User::insert($arrUser);
                    for ($i = 0; $i < count($arrKey); $i++) {
                        Student::updateOrCreate(
                            $arrKey[$i], $arrUpdateStudent[$i]
                        );
                    }

                    return response()->json([
                        'status' => true
                    ], 200);
                } else {
                    for($i = 0 ; $i< count($arrFile); $i++){
                        unlink(STUDENT_PATH.$arrFileName[$i]);
                    }

                    $arrError[] = [
                        'error' => 'File Lỗi'
                    ];
                    return response()->json([
                        'status' => false,
                        'errors' => $arrError
                    ], 200);
                }
            } else {

                for($i = 0 ; $i< count($arrFile); $i++){
                    unlink(STUDENT_PATH.$arrFileName[$i]);
                }
                return response()->json([
                    'status' => false,
                    'errors' => $arrError
                ], 200);
            }
        }
    }


    public function importStudentListEachSemester(Request $request)
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

            $arrUser = array();
            $arrError = array();

            $arrFileName = array();

            $semesterId = null;
            foreach ($arrFile as $file) {
                $fileName = $this->convert_vi_to_en(str_random(8) . "_" . $file->getClientOriginalName());
                while (File::exists(STUDENT_PATH . $fileName)) {
                    $fileName = $this->convert_vi_to_en(str_random(8) . "_" . $file->getClientOriginalName());
                }
                $file->move(STUDENT_PATH, $fileName);
                $arrFileName[] = $fileName;
                $dataFileExcel = \Maatwebsite\Excel\Facades\Excel::load(STUDENT_PATH . $fileName, function ($reader) {
                })->noHeading()->get();

                $classes = null;
                $monitor = null;
                $semester = null;
                for ($i = 4; $i < count($dataFileExcel); $i++) {
                    if ($i == 4) {
                        // lấy khoa theo Id
                        $facultyName = explode(':', $dataFileExcel[$i][5]);
                        $facultyName = trim($facultyName[1]);
                        $faculty = Faculty::where('name', 'like', "%$facultyName%")->first();

                        if (empty($faculty)) {
                            $arrError[] = "Khoa " . $facultyName . " không tồn tại";
                        }

                        // lấy Lớp theo Id
                        // trường hợp file excel. file thị lớp cột 2. file thì lớp cột 3
                        $className = explode(':', $dataFileExcel[$i][2]);
                        if(empty($className[0])){
                            $className = explode(':', $dataFileExcel[$i][3]);
                        }
                        $className = trim($className[1]);
                        $classes = Classes::where('name', 'like', "%$className%")->first();


                        if (empty($classes)) {
                            $arrError[] = "Lớp " . $className . " không tồn tại";
                        } else {
                            // lấy đưuọc lớp thì lấy ra lớp trưởng của lớp đó.
                            $monitor = DB::table('students')
                                ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
                                ->where([
                                    ['users.role_id', ROLE_BANCANSULOP],
                                    ['students.class_id', $classes->id]
                                ])
                                ->select('students.*')
                                ->first();
                            if (empty($monitor)) {
                                $arrError[] = "Ban cán sự lớp " . $className . " không tồn tại";
                            }
                        }

                    } elseif ($i == 5) {
                        //lấy học kì từ học kì và năm học
                        $term = explode(':', $dataFileExcel[$i][2]);
                        if(empty($term[0])){
                            $term = explode(':', $dataFileExcel[$i][3]);
                        }
                        $term = trim($term[1]);

                        switch ($term) {
                            case "II":
                                $term = 2;
                                break;
                            case "I":
                                $term = 1;
                                break;
                        }
                        $year = explode(':', $dataFileExcel[$i][5]);
                        $year = explode('-', trim($year[1]));
                        $yearFrom = trim($year[0]);
                        $yearTo = trim($year[1]);

                        $semester = Semester::where([
                            ['year_from' , $yearFrom],
                            ['year_to' , $yearTo],
                            ['term' , $term],
                        ])->first();
                        
                        if (empty($semester)) {
                            $arrError[] = "Học kì $term năm học $yearFrom - $yearTo không tồn tại";
                        }else{
                            $semesterId = $semester->id;
                        }
                    }
                    elseif (!empty($dataFileExcel[$i][1]) AND !empty($dataFileExcel[$i][0]) AND $i >= 10 AND !empty($faculty) AND !empty($classes) AND !empty($semester) AND !empty($monitor)) {
                        $arrUser[] = [
                            'class_id' => $classes->id,
                            'user_id' => $dataFileExcel[$i][1],
                            'semester_id' => $semester->id,
                            'monitor_id' => $monitor->user_id,
                            'staff_id' => $classes->staff_id,
                        ];
                    }
                }
            }

            $arrFileImport = array();
            $userLogin = Auth::user();
            if (empty($arrError)) {
                // kiểm tra xem có user nào k tồn tại trong DB k? nếu có thì lấy rồi xuất ra thông báo
                foreach($arrUser as $key => $value){
                    $userSearch = User::where('users_id', $value['user_id'])->first();
                    if(empty($userSearch)){
                        $arrError[] = "MSSV $value[user_id] không tồn tại trong danh sách sinh viên";
                    }
                }
                if(!empty($arrError)){
                    //vì trên kia đã lưu file. nên cho dù đc hay k thì đều lưu lại lịch sử import
                    // =>>>> đổi thành nếu có lỗi thì xóa file đi.đỡ tốn bộ nhớ
                    for($i = 0 ; $i< count($arrFile); $i++){
                        unlink(STUDENT_PATH.$arrFileName[$i]);
                    }
                    $arrError = array_merge(['Vui lòng thêm tài khoản cho sinh viên hoặc sửa lại thông tin nếu SV chuyển khoa, sau đó nhập lại File.'],$arrError);
                    return response()->json([
                        'status' => false,
                        'errors' => $arrError
                    ], 200);
                }else{
                    //nếu k có lỗi. di chuyển file qua thư mục mới.
                    for($i = 0 ; $i< count($arrFile); $i++){

                        if (!file_exists(public_path().'/'.STUDENT_LIST_EACH_SEMESTER_PATH.$semesterId)) {
                            mkdir(public_path().'/'.STUDENT_LIST_EACH_SEMESTER_PATH.$semesterId, 0777, true);
                        }
                        if(File::move(STUDENT_PATH.$arrFileName[$i],STUDENT_LIST_EACH_SEMESTER_PATH.$semesterId."/".$arrFile[$i]->getClientOriginalName())) {
                            if (file_exists(STUDENT_PATH . $arrFileName[$i])) {
                                unlink(STUDENT_PATH . $arrFileName[$i]);
                            }
                        }
                        $arrFileImport[] = [
                            'file_path' => $semesterId."/".$arrFile[$i]->getClientOriginalName(),
                            'file_name' => $arrFile[$i]->getClientOriginalName(),
                            'status' => 'Thành công',
                            'staff_id' => $userLogin->Staff->id
                        ];
                    }
                    FileImport::insert($arrFileImport);
                    StudentListEachSemester::insert($arrUser);

                    $this->addEvaluationFormAfterInportStudent($semesterId);
                    return response()->json([
                        'status' => true,
                    ], 200);
                }
            } else {
                // xóa file nếu có lỗi.
                for($i = 0 ; $i< count($arrFile); $i++){
                    unlink(STUDENT_PATH.$arrFileName[$i]);
                }
                return response()->json([
                    'status' => false,
                    'errors' => $arrError
                ], 200);
            }
        }
    }

    private function addEvaluationFormAfterInportStudent($semesterId)
    {
        // lấy ra sinh viên
        $students = Student::rightJoin('student_list_each_semesters','student_list_each_semesters.user_id','=','students.user_id')
            ->where('semester_id',$semesterId)->select('students.*')->get();

        $arrEvaluationForm = array();
        foreach ($students as $value) {
            $arrEvaluationForm[] = [
                'semester_id' => $semesterId,
                'student_id' => $value->id
            ];
        }
        EvaluationForm::insert($arrEvaluationForm);
    }

    public function ajaxGetUsers(Request $request){
        $user = Auth::user();
        $students = $this->getStudentByRoleUserLogin($user);
        return DataTables::of($students)->make(true);
    }
}
