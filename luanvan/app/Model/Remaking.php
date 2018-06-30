<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Remaking extends Model
{
    protected $table = 'remakings';

    protected $fillable = ['remarking_reason','remarking_reply','reply_by','status','evaluation_form_id','old_score'];

    public function EvaluationForm(){
        return $this->belongsTo('App\Model\EvaluationForm','evaluation_form_id','id');
    }

    public function Staff(){
        return $this->belongsTo('App\Model\Staff','reply_by','id');
    }
}
