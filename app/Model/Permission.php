<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['name','display_name','description'];

    public function Roles(){
        return $this->belongsToMany('App\Model\Role','role_permissions');
    }
}
