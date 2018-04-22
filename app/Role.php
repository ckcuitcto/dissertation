<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['name','display_name','description'];

    public function Users(){
        return $this->hasMany('App\User','role_id','id');
    }

    public function Permissions(){
        return $this->belongsToMany('App\Permission','role_permissions','role_id','permission_id');
    }
}
