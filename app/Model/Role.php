<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['name', 'display_name', 'description'];

    public function Users()
    {
        return $this->hasMany('App\Model\User', 'role_id', 'id');
    }

    public function Permissions()
    {
        return $this->belongsToMany('App\Model\Permission', 'role_permissions', 'role_id', 'permission_id');
    }

    public function MarkTimes()
    {
        return $this->hasMany('App\Model\MarkTime', 'role_id', 'id');
    }
}
