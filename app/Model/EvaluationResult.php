<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EvaluationResult extends Model
{
    protected $table = "evaluation_results";

    protected $fillable = [ 'evaluation_criteria_id', 'evaluation_form_id','marker_id','marker_score'];

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
        return $this->belongsTo('App\Model\User', 'marker_id', 'id');
    }

}
