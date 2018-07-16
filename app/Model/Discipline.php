<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $table = 'disciplines';

    public $timestamps = false;

//    protected $fillable = ['user_id','semester_id','evaluation_criteria_id','score_minus','reason'];
    protected $fillable = ['user_id','semester_id','discipline_reason_id'];

    public function Student(){
        return $this->belongsTo('App\Model\Student','user_id','user_id');
    }

    public function Semester(){
        return $this->belongsTo('App\Model\Semester','semester_id','id');
    }

    public function DisciplineReason(){
        return $this->belongsTo('App\Model\DisciplineReason','discipline_reason_id','id');
    }
//    public function EvaluationCriteria(){
//        return $this->belongsTo('App\Model\EvaluationCriteria','evaluation_criteria_id','id');
//    }

}
