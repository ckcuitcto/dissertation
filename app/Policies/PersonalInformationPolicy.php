<?php

namespace App\Policies;

use App\Model\Student;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalInformationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the student.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Student  $student
     * @return mixed
     */
    public function view(User $user, Student $student)
    {
        if($user->role_id >= 3){
            return $user->faculty_id == $student->User->faculty_id OR $user->role_id >= 5;
        }else{
            return $user->id == $student->user_id;
        }

    }
}
