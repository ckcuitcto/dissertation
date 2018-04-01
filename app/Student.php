<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email','gender','address','phone_number','birthday','avatar','role_id','class_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Comments(){
        return $this->hasMany('App\Comment','created_by','id');
    }

    public function Proofs(){
        return $this->hasMany('App\Proof','created_by','id');
    }

    public function NotificationStudents(){
        return $this->hasMany('App\NotificationStudent','student_id','id');
    }

    public function EvaluationForms(){
        return $this->hasMany('App\EvaluationForm','student_id','id');
    }

    public function EvaluationResults(){
        return $this->hasMany('App\EvaluationResult','student_id','id');
    }

    public function Role(){
        return $this->belongsTo('App\Role','role_id','id');
    }

    public function Classes(){
        return $this->belongsTo('App\Classes','class_id','id');
    }
}
