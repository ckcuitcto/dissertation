<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','user_id','class_id'];

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

    public function Classes(){
        return $this->belongsTo('App\Classes','class_id','id');
    }

    public function User(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
