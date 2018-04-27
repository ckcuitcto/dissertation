<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EvaluationResult extends Model
{
    protected $table = "evaluation_results";

    protected $fillable = ['score', 'evaluation_criteria_id', 'evaluation_form_id', 'user_id'];

    public function EvaluationForm()
    {
        return $this->belongsTo('App\Model\EvaluationForm', 'evaluation_form_id', 'id');
    }

    public function EvaluationCriteria()
    {
        return $this->belongsTo('App\Model\EvaluationCriteria', 'evaluation_criteria_id', 'id');
    }

    public function Markers()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }

}
