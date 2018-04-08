<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = ['name','faculty_id','staff_id'];

    public function Student(){
        return $this->hasMany('App\Student','class_id','id');
    }

    public function Staff(){
        return $this->belongsTo('App\Staff','staff_id','id');
    }
}
