<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationResult extends Model
{
    protected $table = "evaluation_results";

    protected $fillable = ['score', 'evaluation_criteria_id', 'evaluation_form_id', 'user_id'];

    public function EvaluationForm()
    {
        return $this->belongsTo('App\EvaluationForm', 'evaluation_form_id', 'id');
    }

    public function EvaluationCriteria()
    {
        return $this->belongsTo('App\EvaluationCriteria', 'evaluation_criteria_id', 'id');
    }

    public function Markers()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
