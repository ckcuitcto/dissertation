<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarkTime extends Model
{
    protected $table = 'mark_times';

    protected $fillable = ['mark_time_start','mark_time_end','semester_id','role_id'];

    public function Semester(){
        return $this->belongsTo('App\Semester','semester_id','id');
    }

    public function Role(){
        return $this->belongsTo('App\Role','role_id','id');
    }
}
