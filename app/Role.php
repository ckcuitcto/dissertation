<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['name'];

    public function Users(){
        return $this->hasMany('App\User','role_id','id');
    }

    public function Permissions(){
        return $this->belongsToMany('App\Permission','role_permissions');
    }
}
