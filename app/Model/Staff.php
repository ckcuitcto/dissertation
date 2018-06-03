<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Model
{

    protected $table = 'staff';

    protected $fillable = ['id','user_id'];

    public function Classes(){
        return $this->hasMany('App\Model\Classes','staff_id','id');
    }

    public function Notifications(){
        return $this->hasMany('App\Model\Notifications','created_by','id');
    }

    public function User(){
        return $this->belongsTo('App\Model\User','user_id','users_id');
    }
}
