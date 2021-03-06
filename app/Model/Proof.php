<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
    protected $table = 'proofs';

    protected $fillable = ['name','semester_id','created_by','evaluation_criteria_id','note','valid'];

    public function Student(){
        return $this->belongsTo('App\Model\Student','created_by','id');
    }

    public function Semester(){
        return $this->belongsTo('App\Model\Semester','semester_id','id');
    }

    public function EvaluationCriteria(){
        return $this->belongsTo('App\Model\EvaluationCriteria','evaluation_criteria_id','id');
    }
}
