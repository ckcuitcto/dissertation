<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';

    protected $fillable = ['title','max_score','parent_id'];


    public function TopicChild(){
        return $this->hasMany('App\Model\Topic','parent_id','id');
    }

    public function EvaluationCriterias(){
        return $this->hasMany('App\Model\EvaluationCriteria','topic_id','id');
    }

}
