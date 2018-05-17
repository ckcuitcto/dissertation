<?php

namespace App\Policies;

use App\Model\MarkTime;
use App\Model\User;
use App\Model\Proof;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProofPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the proof.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Proof  $proof
     * @return mixed
     */
    public function view(User $user, Proof $proof)
    {
        //
    }

    /**
     * Determine whether the user can create proofs.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the proof.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Proof  $proof
     * @return mixed
     */
    public function update(User $user, Proof $proof)
    {
        //
    }

    /**
     * Determine whether the user can delete the proof.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Proof  $proof
     * @return mixed
     */
    public function delete(User $user, Proof $proof)
    {
//        if($user->Student->id == $proof->created_by){
//            $markTime = MarkTime::where([
//                ['role_id' => $user->role_id],
//                ['semester_id' => $proof->Semester->id]
//            ])->fisrt();
//
//            $valid1 = strtotime($markTime->mark_time_start) <= strtotime(Carbon::now()->format('Y-m-d'));
//            $valid2 = strtotime($markTime->mark_time_end) >= strtotime(Carbon::now()->format('Y-m-d'));
//            if($valid1 AND $valid2)
//            {
//                return true;
//            }
//        }
//        return false;
    }
}
