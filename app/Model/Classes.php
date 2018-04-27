<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = ['name','faculty_id','staff_id'];

    public function Students(){
        return $this->hasMany('App\Model\Student','class_id','id');
    }

    public function Staff(){
        return $this->belongsTo('App\Model\Staff','staff_id','id');
    }

    public function Faculty(){
        return $this->belongsTo('App\Model\Faculty','faculty_id','id');
    }
}
