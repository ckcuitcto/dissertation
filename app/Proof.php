<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
    protected $table = 'proofs';

    protected $fillable = ['name','semester_id','created_by'];

    public function Student(){
        return $this->belongsTo('App\Student','created_by','user_id');
    }

    public function Semester(){
        return $this->belongsTo('App\Semester','semester_id','id');
    }
}
