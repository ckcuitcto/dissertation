<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{

    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','class_id'];

    public function Comments(){
        return $this->hasMany('App\Comment','created_by','user_id');
    }

    public function Proofs(){
        return $this->hasMany('App\Proof','created_by','user_id');
    }

    public function NotificationStudents(){
        return $this->hasMany('App\NotificationStudent','student_id','user_id');
    }

    public function EvaluationForms(){
        return $this->hasMany('App\EvaluationForm','student_id','user_id');
    }

    public function Classes(){
        return $this->belongsTo('App\Classes','class_id','id');
    }
}
