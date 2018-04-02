<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $table = 'faculties';

    protected $fillable = ['name'];

    public function Classes(){
        return $this->hasMany('App\Classes','faculty_id','id');
    }

}
