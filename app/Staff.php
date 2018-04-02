<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use Notifiable;
    protected $table = 'staff';

    protected $fillable = ['name','email','gender','address','phone_number','birthday','avatar','role_id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Classes(){
        return $this->hasMany('App\Classes','education_adviser_id','id');
    }

    public function Notifications(){
        return $this->hasMany('App\Notifications','created_by','id');
    }

    public function Role(){
        return $this->belongsTo('App\Role','role_id','id');
    }

    public function EvaluationResults(){
        return $this->hasMany('App\EvaluationResult','staff_id','id');
    }
}
