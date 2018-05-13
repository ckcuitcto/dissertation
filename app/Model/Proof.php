<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
    protected $table = 'proofs';

    protected $fillable = ['name','semester_id','created_by','evaluation_criteria_id'];

    public function Student(){
        return $this->belongsTo('App\Model\Student','created_by','id');
    }

    public function Semester(){
        return $this->belongsTo('App\Model\Semester','semester_id','id');
    }
}
