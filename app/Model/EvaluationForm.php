<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EvaluationForm extends Model
{
    protected $table = "evaluation_forms";

    protected $fillable = ['id','total', 'semester_id', 'student_id','status'];

    public function Semester()
    {
        return $this->belongsTo('App\Model\Semester', 'semester_id', 'id');
    }

    public function Student()
    {
        return $this->belongsTo('App\Model\Student', 'student_id', 'id');
    }

    public function EvaluationResults(){
        return $this->hasMany('App\Model\EvaluationResult','evaluation_form_id','id');
    }

    public function Remaking(){
        return $this->hasMany('App\Model\Remaking','evaluation_form_id','id');
    }
}
