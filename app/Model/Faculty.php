<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $table = 'faculties';

    protected $fillable = ['name'];

    public function Classes(){
        return $this->hasMany('App\Model\Classes','faculty_id','id');
    }

    public function Users(){
        return $this->hasMany('App\Model\User','faculty_id','id');
    }

    public function News(){
        return $this->hasMany('App\Model\News','faculty_id','id');
    }

}
