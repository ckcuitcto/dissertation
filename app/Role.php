<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['name'];

    public function Students(){
        return $this->hasMany('App\Student','role_id','id');
    }

    public function Staff(){
        return $this->hasMany('App\Staff','role_id','id');
    }
}
