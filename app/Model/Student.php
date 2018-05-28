<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','user_id','class_id','academic_year_from','academic_year_to','status'];

    public function Comments(){
        return $this->hasMany('App\Model\Comment','created_by','id');
    }

    public function Proofs(){
        return $this->hasMany('App\Model\Proof','created_by','id');
    }

    public function NotificationStudents(){
        return $this->hasMany('App\Model\NotificationStudent','student_id','id');
    }

    public function EvaluationForms(){
        return $this->hasMany('App\Model\EvaluationForm','student_id','id');
    }

    public function Classes(){
        return $this->belongsTo('App\Model\Classes','class_id','id');
    }

    public function User(){
        return $this->belongsTo('App\Model\User','user_id','id');
    }
}
