<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationResult extends Model
{
    protected $table = "evaluation_results";

    protected $fillable = ['score','evaluation_criteria_id','evaluation_form_id','student_id','staff_id'];

        public function EvaluationForm(){
        return $this->belongsTo('App\EvaluationForm','evaluation_form_id','id');
    }

    public function EvaluationCriteria()
    {
        return $this->belongsTo('App\EvaluationCriteria','evaluation_criteria_id','id');
    }

    public function Student()
    {
        return $this->belongsTo('App\Student','student_id','id');
    }

    public function Staff()
    {
        return $this->belongsTo('App\Staff','student_id','id');
    }
}
