<?php

namespace App\Http\Controllers;

use App\Model\Semester;
use App\Model\Student;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $dataTable;
    protected $userLogin;

    protected function getUserLogin() {
        if (!$this->userLogin) {
            $this->userLogin = Auth::user();
        }
        return $this->userLogin;
    }

    public function dataTable($query)
    {
        return new EloquentDataTable($query);
    }

    protected function getCurrentSemester()
    {
        $semester = Semester::where('date_start', '<=', Carbon::now()->format('Y/m/d'))->orderBy('date_start', 'desc')->first();
        return $semester;
    }

    public function getStudentByRoleUserLogin(User $user, $options = array())
    {
        if(empty($options)) {
//            $semester = $this->getCurrentSemester();
            if ($user->Role->weight >= ROLE_PHONGCONGTACSINHVIEN) // admin va phong ctsv thì lấy tất cả user
            {
                $arrUserId = DB::table('users')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                    ->select('users.users_id')->get()->toArray();
            } elseif ($user->Role->weight >= ROLE_BANCHUNHIEMKHOA) {
                // neeus laf ban chu nhiem khoa thi lay cung khoa
                $arrUserId = DB::table('users')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->where('users.faculty_id', $user->faculty_id)
                    ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                    ->select('users.users_id')->get()->toArray();
            } elseif ($user->Role->weight >= ROLE_COVANHOCTAP) {
                // neeus laf ban co van hoc tap thi lay cac sinh vien thuoc cac lop ma ng nay lam co  van
                // lấy danh sách các lớp mà ng này làm cố vấn
                $arrClassId = [];
                foreach ($user->Staff->Classes as $class) {
                    $arrClassId[] = $class->id;
                }
                $arrUserId = DB::table('users')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->leftJoin('students', 'students.user_id', '=', 'users.users_id')
                    ->where('users.faculty_id', $user->faculty_id)
                    ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                    ->whereIn('students.class_id', $arrClassId)
                    ->select('users.users_id')->get()->toArray();

            } else if ($user->Role->weight >= ROLE_BANCANSULOP) //ban can su lop, thì lấy user thuộc lop
            {
                $arrUserId = DB::table('users')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->leftJoin('students', 'students.user_id', '=', 'users.users_id')
                    ->where('users.faculty_id', $user->faculty_id)
                    ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                    ->where('students.class_id', $user->Student->class_id)
                    ->select('users.users_id')->get()->toArray();
            }
            foreach ($arrUserId as $key => $value) {
                $userIds[$key] = [$value->users_id];
            }
            if (!empty($userIds)) {
                $students = DB::table('student_list_each_semesters')
                    ->leftJoin('classes', 'classes.id', '=', 'student_list_each_semesters.class_id')
                    ->leftJoin('students', 'students.user_id', '=', 'student_list_each_semesters.user_id')
                    ->leftJoin('users', 'users.users_id', '=', 'students.user_id')
                    ->leftJoin('faculties', 'faculties.id', '=', 'users.faculty_id')
                    ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                    ->leftJoin('evaluation_forms', 'evaluation_forms.student_id', '=', 'students.id')
                    ->whereIn('users.users_id', $userIds)
//                    ->where('student_list_each_semesters.semester_id', $semester->id)
                    ->select(
                        'users.users_id',
                        'users.name as userName',
                        'roles.display_name',
                        'classes.name as className',
                        'faculties.name as facultyName',
                        'students.academic_year_from',
                        'students.academic_year_to',
                        DB::raw("CONCAT(students.academic_year_from,'-',students.academic_year_to) as academic"),
                        'students.id',
                        'users.status',
                        'students.id as studentId',
                        'users.faculty_id',
                        'students.class_id',
                        'roles.id as role_id',
                        'student_list_each_semesters.semester_id as semesterId',
                        'evaluation_forms.total as totalScore'
                    )->distinct();
                return $students;
            }
            return false;
        }elseif(!empty($options['all'])){
            $students = DB::table('users')
                ->leftJoin('students', 'users.users_id', '=', 'students.user_id')
                ->leftJoin('classes', 'classes.id', '=', 'students.class_id')
                ->leftJoin('faculties', 'faculties.id', '=', 'users.faculty_id')
                ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
                ->select(
                    'users.users_id',
                    'users.name as userName',
                    'roles.display_name',
                    'classes.name as className',
                    'faculties.name as facultyName',
                    'students.academic_year_from',
                    'students.academic_year_to',
                    DB::raw("CONCAT(students.academic_year_from,'-',students.academic_year_to) as academic"),
                    'students.id',
                    'users.status',
                    'users.faculty_id',
                    'students.class_id'
//                    DB::raw("CONCAT(students.academic_year_from,'-',students.academic_year_to) as academic")
                );
            return $students;
        }
    }

    public function getStudentByRoleUserLoginNotUseDataTable(User $user,$pagination = TRUE,$options = array())
    {
        $semester = $this->getCurrentSemester();
        //mặc định là lấy tất cả sinh viên hiện tại
        //nếu có option all thì lấy tất cả sinh viên tỏng danh sách
        if ($user->Role->weight >= ROLE_PHONGCONGTACSINHVIEN) // admin va phong ctsv thì lấy tất cả user
        {
            if(!empty($options['all'])){
                if ($pagination == TRUE) {
                    $students = Student::paginate(50);
                }else{
                    $students = Student::all();
                }
            }else{
                if ($pagination == TRUE) {
                    $students = Student::rightJoin('student_list_each_semesters', 'student_list_each_semesters.user_id', '=', 'students.user_id')
                        ->where('student_list_each_semesters.semester_id',$semester->id)
                        ->select('students.*')
                        ->paginate(50);
                }else{
                    $students = Student::rightJoin('student_list_each_semesters', 'student_list_each_semesters.user_id', '=', 'students.user_id')
                        ->where('student_list_each_semesters.semester_id',$semester->id)
                        ->select('students.*')
                        ->get();
                }
            }
            return $students;
        } elseif ($user->Role->weight >= ROLE_BANCHUNHIEMKHOA) {
            // neeus laf ban chu nhiem khoa thi lay cung khoa
            $arrUserId = DB::table('users')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->where('users.faculty_id', $user->faculty_id)
                ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                ->select('users.users_id')->get()->toArray();
        } elseif ($user->Role->weight >= ROLE_COVANHOCTAP) {
            // neeus laf ban co van hoc tap thi lay cac sinh vien thuoc cac lop ma ng nay lam co  van
            // lấy danh sách các lớp mà ng này làm cố vấn
            $arrClassId = [];
            foreach ($user->Staff->Classes as $class) {
                $arrClassId[] = $class->id;
            }
            $arrUserId = DB::table('users')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('students', 'students.user_id', '=', 'users.users_id')
                ->where('users.faculty_id', $user->faculty_id)
                ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                ->whereIn('students.class_id', $arrClassId)
                ->select('users.users_id')->get()->toArray();
        } else if ($user->Role->weight >= ROLE_BANCANSULOP) //ban can su lop, thì lấy user thuộc lop
        {
            $arrUserId = DB::table('users')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('students', 'students.user_id', '=', 'users.users_id')
                ->where('users.faculty_id', $user->faculty_id)
                ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                ->where('students.class_id', $user->Student->class_id)
                ->select('users.users_id')->get()->toArray();
        }
        foreach ($arrUserId as $key => $value){
            $userIds[$key] = [$value->users_id];
        }

        //nếu có options only-get-id thì rettủn về mảng user;
        if(($options['only-get-id'])){
            return $userIds;
        }
        if (!empty($userIds)) {
            if(!empty($options['all'])){
                if ($pagination == TRUE) {
                    $students = Student::whereIn('user_id', $userIds)->paginate(50);
                }else{
                    $students = Student::whereIn('user_id', $userIds)->get();
                }
            }else{
                if ($pagination == TRUE) {
                    $students = Student::rightJoin('student_list_each_semesters', 'student_list_each_semesters.user_id', '=', 'students.user_id')
                        ->select('students.*')
                        ->whereIn('students.user_id', $userIds)
                        ->where('student_list_each_semesters.semester_id',$semester->id)
                        ->paginate(50);
                }else{
                    $students = Student::rightJoin('student_list_each_semesters', 'student_list_each_semesters.user_id', '=', 'students.user_id')
                        ->where('student_list_each_semesters.semester_id',$semester->id)
                        ->select('students.*')
                        ->whereIn('students.user_id', $userIds)->get();
                }
            }

            return $students;
        }
        return false;
    }

    public function formatDate($date)
    {
        return Carbon::createFromFormat('d/m/Y', $date);
    }

    public function checkImageFile($name)
    {
        $mime = explode('.', $name)[count(explode('.', $name)) - 1];
        if (in_array($mime, FILE_IMAGE)) {
            return true;
        }
        return false;
    }

    public static function getDisplayStatusStudent($status)
    {
        switch ($status) {
            case STUDENT_STUDYING:
                return "Đang học";
                break;
            case STUDENT_DEFERMENT:
                return "Bảo lưu";
                break;
            case STUDENT_DROP_OUT:
                return "Bỏ học";
                break;
            case STUDENT_GRADUATE:
                return "Tốt nghiệp";
                break;
            default:
                return "Đang học";
        }
    }

    public static function getDisplayGender($gen)
    {
        switch ($gen) {
            case MALE:
                return "Nam";
            case FEMALE:
                return "Nữ";
            default:
                return "Khác";
        }
    }

    public static function getDisplayStatusUser($status)
    {
        switch ($status) {
            case USER_ACTIVE:
                return "Hoạt động";
            case USER_INACTIVE:
                return "Ngừng hoạt động";
            default:
                return "Hoạt động";
        }
    }

    public static function getDisplayStatusRemaking($status)
    {
        switch ($status) {
            case HANDLE:
                return "Đang xử lí";
            case RESOLVED:
                return "Đã giải quyết";
            default:
                return "Đang xử lí";
        }
    }

    public static function checkInTime($timeStart, $timeEnd, $timeCheck = null)
    {
        if (empty($timeStart) OR empty($timeEnd)) {
            return false;
        }
        if (empty($timeCheck)) {
            $timeCheck = Carbon::now()->format('Y-m-d');
        }
        if (strtotime($timeCheck) >= strtotime($timeStart) AND strtotime($timeCheck) <= strtotime($timeEnd)) {
            return true;
        }
        return false;
    }

    public function convert_vi_to_en($str)
    {
        $str = preg_replace("(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)", 'a', $str);
        $str = preg_replace("(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)", 'e', $str);
        $str = preg_replace("(ì|í|ị|ỉ|ĩ)", 'i', $str);
        $str = preg_replace("(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)", 'o', $str);
        $str = preg_replace("(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)", 'u', $str);
        $str = preg_replace("(ỳ|ý|ỵ|ỷ|ỹ)", 'y', $str);
        $str = preg_replace("(đ)", 'd', $str);
        $str = preg_replace("(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)", 'A', $str);
        $str = preg_replace("(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)  ", 'E', $str);
        $str = preg_replace("(Ì|Í|Ị|Ỉ|Ĩ)", 'I', $str);
        $str = preg_replace("(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)", 'O', $str);
        $str = preg_replace("(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)", 'U', $str);
        $str = preg_replace("(Ỳ|Ý|Ỵ|Ỷ|Ỹ)", 'Y', $str);
        $str = preg_replace("(Đ)", 'D', $str);
        //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
        return $str;
    }

    public function checkRank1($score){
        if($score >= EXCELLENT){
            return "Xuất sắc";
        }elseif($score >= VERY_GOOD ) {
            return "Tốt";
        }elseif($score >= GOOD ) {
            return "Khá";
        }elseif($score >= AVERAGE ) {
            return "Trung bình";
        }elseif($score >= POOR ) {
            return "Yếu";
        }elseif($score >= BAD ) {
            return "Kém";
        }
    }

    public static function getDisplayStatusEvaluationForm($status)
    {
        if($status != -1){
            $userLogin = Auth::user();
            $binary = (string)decbin($status);
            $arrBinary = str_split($binary);
            if(!empty($arrBinary[$userLogin->Role->weight - 1]) AND $arrBinary[$userLogin->Role->weight - 1] == 1){
                return "Đã chấm";
            }else{
                return "Chưa chấm";
            }
        }else{
            return "Hoàn thành";
        }

    }

    public function getStatusEvaluationForm($status)
    {
        $userLogin = $this->getUserLogin();
        $binary = (string)decbin($status);
        $arrBinary = str_split($binary);
        if(!empty($arrBinary[$userLogin->Role->weight - 1]) AND $arrBinary[$userLogin->Role->weight - 1] == 1){
            return true;
        }else{
            return false;
        }
    }
}
