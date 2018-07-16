<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    protected $table = 'evaluation_criterias';

    protected $fillable = ['content','detail','mark_range_display','mark_range_from','mark_range_to','max_score','parent_id','proof','level','step_html'];
//    protected $fillable = ['content','detail','mark_range_display','mark_range_from','mark_range_to','topic_id','parent_id'];

    public function Child(){
        return $this->hasMany('App\Model\EvaluationCriteria','parent_id','id');
    }

    public function EvaluationResult(){
        return $this->hasMany('App\Model\EvaluationResult','evaluation_criteria_id','id');
    }

    public function Proof(){
        return $this->hasMany('App\Model\Proof','evaluation_criteria_id','id');
    }

//    public function Disciplines(){
//        return $this->hasMany('App\Model\Disciplines','evaluation_criteria_id','id');
//    }

    public function DisciplineReason(){
        return $this->hasMany('App\Model\DisciplineReason','evaluation_criteria_id','id');
    }

}
