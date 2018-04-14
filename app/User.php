<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email','gender','address','phone_number','birthday','avatar','role_id','faculty_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function EvaluationResults(){
        return $this->hasMany('App\EvaluationResult','marker_id','id');
    }

    public function Role(){
        return $this->belongsTo('App\Role','role_id','id');
    }

    public function Student(){
        return $this->hasOne('App\Student','user_id','id');
    }

    public function Staff(){
        return $this->hasOne('App\Staff','user_id','id');
    }

    public function Faculty(){
        return $this->belongsTo('App\User','faculty_id','id');
    }

    public function hasPermission(Permission $permission){

        //contains dung để kiểm tra xem nó có chứa permission k
        // ep kiểu boolean
        // optional là khi mà trong Role k có Permission thì ucngx k báo lỗi
        return !! optional(optional($this->Role)->Permissions)->contains($permission);
    }
}
