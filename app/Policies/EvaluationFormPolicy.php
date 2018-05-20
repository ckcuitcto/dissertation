<?php

namespace App\Policies;

use App\Model\User;
use App\Model\EvaluationForm;
use Illuminate\Auth\Access\HandlesAuthorization;

class EvaluationFormPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the evaluationForm.
     *
     * @param  \App\Model\User $user
     * @param  \App\Model\EvaluationForm $evaluationForm
     * @return mixed
     */
    public function view(User $user, EvaluationForm $evaluationForm)
    {
        //nếu là phòng ctsv or > thì xem đc tất cả
        // nếu là khoa  thì xem đc cùng khoa
        // cvht  chri xem dc cac lop là cvht
        if($user->Role->weight >= ROLE_PHONGCONGTACSINHVIEN ){
            return true;
        }elseif($user->Role->weight >= ROLE_BANCHUNHIEMKHOA ){
            return $user->faculty_id == $evaluationForm->Student->User->faculty_id;
        }elseif($user->Role->weight >= ROLE_COVANHOCTAP ){
            // nêu là cố vấn học tập thì sinh viên chủ form phải thuộc lớp của CVHT
            foreach($user->Staff->Classes as $class){
                if($class->id == $evaluationForm->Student->Classes->id){
                    return true;
                }
            }
            return false;
//            return $user->Staff->Classes->id == $evaluationForm->Student->Classes->id;
        }elseif($user->Role->weight >= ROLE_BANCANSULOP ){
            // nếu là ban cán sự lớp thì phhải cùng lớp
            return $user->Student->Classes->id == $evaluationForm->Student->Classes->id;
        }elseif($user->Student->id == $evaluationForm->student_id){
            // nếu là ính viên thì phải là chủ form
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create evaluationForms.
     *
     * @param  \App\Model\User $user
     * @return mixed
     */
    public
    function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the evaluationForm.
     *
     * @param  \App\Model\User $user
     * @param  \App\Model\EvaluationForm $evaluationForm
     * @return mixed
     */
    public
    function update(User $user, EvaluationForm $evaluationForm)
    {
        //
    }

    /**
     * Determine whether the user can delete the evaluationForm.
     *
     * @param  \App\Model\User $user
     * @param  \App\Model\EvaluationForm $evaluationForm
     * @return mixed
     */
    public
    function delete(User $user, EvaluationForm $evaluationForm)
    {
        //
    }
}
