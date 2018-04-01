<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    //
    protected $table = 'semesters';

    protected $fillable = ['year','term'];

    public function Proof(){
        return $this->hasMany('App\Proof','semester_id','id');
    }

    public function EvaluationForm(){
        return $this->hasMany('App\EvaluationForm','semester_id','id');
    }

}
