<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Model
{

    protected $table = 'staff';

    protected $fillable = ['user_id'];

    public function Classes(){
        return $this->hasMany('App\Classes','staff_id','user_id');
    }

    public function Notifications(){
        return $this->hasMany('App\Notifications','created_by','user_id');
    }

    public function User(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
