<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisciplineReason extends Model
{
    protected $table = 'discipline_reasons';

    public $timestamps = false;

    protected $fillable = ['reason','score_minus','evaluation_criteria_id'];

    public function EvaluationCriteria(){
        return $this->belongsTo('App\Model\EvaluationCriteria','evaluation_criteria_id','id');
    }

    public function Disciplines(){
        return $this->hasMany('App\Model\Disciplines','discipline_reason_id','id');
    }
}
