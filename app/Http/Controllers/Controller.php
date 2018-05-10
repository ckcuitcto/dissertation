<?php

namespace App\Http\Controllers;

use App\Model\Student;
use App\Model\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getStudentByRoleUserLogin(User $user){
        if ($user->Role->id >= 5) // admin va phong ctsv thì lấy tất cả user
        {
            $students = Student::all();
        } elseif ($user->Role->id >= 2) //ban can su lop, co van hoc tpa, chu nhiem khoa thì lấy user thuộc khoa giống
        {
            $users = array_flatten(User::where('faculty_id', $user->faculty_id)->where('role_id', '<=', 2)->select('id')->get()->toArray());
            $students = Student::whereIn('user_id', $users)->get();
        }

        return $students;
    }
}
