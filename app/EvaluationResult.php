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
        return $this->belongsTo('App\Student','monitor_id','id');
    }

    public function MarkerStaff()
    {
        return $this->belongsTo('App\Staff','student_id','id');
    }

    public function MarkerEducationAdviser()
    {
        return $this->belongsTo('App\Staff','education_adviser_id','id');
    }

    public function MarkerFaculty()
    {
        return $this->belongsTo('App\Staff','faculty_id','id');
    }

    public function MarkerCustom()
    {
        return $this->belongsTo('App\Staff','custom_id','id');
    }
}
