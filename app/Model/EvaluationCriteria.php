<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    protected $table = 'evaluation_criterias';

    protected $fillable = ['content','detail','mark_range_display','mark_range_from','mark_range_to','topic_id','parent_id'];

    public function Topic(){
        return $this->belongsTo('App\Model\Topic','topic_id','id');
    }

    public function Parent(){
        return $this->hasMany('App\Model\EvaluationCriteria','parent_id','id');
    }

    public function EvaluationResult(){
        return $this->hasMany('App\Model\EvaluationResult','evaluation_criteria_id','id');
    }

}
