<?php
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    //
    protected $table = 'semesters';

    protected $fillable = ['year','term'];

    public function Proof(){
        return $this->hasMany('App\Model\Proof','semester_id','id');
    }

    public function EvaluationForm(){
        return $this->hasMany('App\Model\EvaluationForm','semester_id','id');
    }

}
