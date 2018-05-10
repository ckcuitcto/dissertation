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
     * @param  \App\Model\User  $user
     * @param  \App\Model\EvaluationForm  $evaluationForm
     * @return mixed
     */
    public function view(User $user, EvaluationForm $evaluationForm)
    {
        return $user->faculty_id == $evaluationForm->Student->User->faculty_id OR $user->role_id >= 5;
    }

    /**
     * Determine whether the user can create evaluationForms.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the evaluationForm.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\EvaluationForm  $evaluationForm
     * @return mixed
     */
    public function update(User $user, EvaluationForm $evaluationForm)
    {
        //
    }

    /**
     * Determine whether the user can delete the evaluationForm.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\EvaluationForm  $evaluationForm
     * @return mixed
     */
    public function delete(User $user, EvaluationForm $evaluationForm)
    {
        //
    }
}
