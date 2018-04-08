<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';

    protected $fillable = ['title','max_score','parent_id'];


    public function TopicParent(){
        return $this->belongsTo('App\Topic','parent_id','id');
    }

    public function EvaluationCriterias(){
        return $this->hasMany('App\EvaluationCriteria','topic_id','id');
    }

}
