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


    public function EvaluationForms(){
        return $this->hasMany('App\Model\EvaluationForm','student_id','id');
    }

    public function Classes(){
        return $this->belongsTo('App\Model\Classes','class_id','id');
    }

    public function User(){
        return $this->belongsTo('App\Model\User','user_id','users_id');
    }

    public function StudentListEachSemester(){
        return $this->hasMany('App\Model\StudentListEachSemester','user_id','user_id');
    }

    public function StudentListEachSemesterMonitor(){
        return $this->hasMany('App\Model\StudentListEachSemester','monitor_id','user_id');
    }

    public function Disciplines(){
        return $this->hasMany('App\Model\Discipline','user_id','user_id');
    }

    public function AcademicTranscripts(){
        return $this->hasMany('App\Model\AcademicTranscripts','user_id','user_id');
    }
}
