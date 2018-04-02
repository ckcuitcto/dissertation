<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';

    protected $fillable = ['title','max_score','parent_id'];

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

    public function Role(){
        return $this->belongsTo('App\Role','role_id','id');
    }
}
