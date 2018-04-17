<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['name','title'];

    public function Roles(){
        return $this->belongsToMany('App\Role','role_permissions');
    }
}
