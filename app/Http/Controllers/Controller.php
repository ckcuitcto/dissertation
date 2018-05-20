<?php

namespace App\Http\Controllers;

use App\Model\Student;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getStudentByRoleUserLogin(User $user){
        if ($user->Role->weight >= ROLE_PHONGCONGTACSINHVIEN) // admin va phong ctsv thì lấy tất cả user
        {
            $students = Student::all();
            return $students;
        } elseif ($user->Role->weight >= ROLE_BANCANSULOP) //ban can su lop, co van hoc tpa, chu nhiem khoa thì lấy user thuộc khoa giống
        {
            $arrUserId = DB::table('users')
                ->leftJoin('roles','users.role_id','=','roles.id')
                ->where('users.faculty_id', $user->faculty_id)
                ->where('roles.weight', '<=', ROLE_BANCANSULOP)
                ->select('users.id')->get()->toArray();
            foreach($arrUserId as $key => $value){
                $userIds[$key] = [$value->id];
            }

            $students = Student::whereIn('user_id', $userIds)->get();
            return $students;
        }

    }

    public function formatDate($date){
        return Carbon::createFromFormat('d/m/Y', $date);
    }

    public function checkImageFile($name){
        $mime = explode('.',$name)[count(explode('.',$name))-1];
        if(in_array($mime,FILE_IMAGE)){
            return true;
        }
        return false;
    }
}
