<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationForm extends Model
{
    protected $table = "evaluation_forms";

    protected $fillable = ['total', 'semester_id', 'student_id'];

    public function Semester()
    {
        return $this->belongsTo('App\Semester', 'semester_id', 'id');
    }

    public function Student()
    {
        return $this->belongsTo('App\Student', 'student_id', 'user_id');
    }

    public function EvaluationResults(){
        return $this->hasMany('App\EvaluationResult','evaluation_form_id','id');
    }
}
