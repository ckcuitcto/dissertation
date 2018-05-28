<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name','email','gender','address','phone_number','birthday','avatar','role_id','faculty_id','status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function EvaluationResults(){
        return $this->hasMany('App\Model\EvaluationResult','marker_id','id');
    }

    public function Role(){
        return $this->belongsTo('App\Model\Role','role_id','id');
    }

    public function Student(){
        return $this->hasOne('App\Model\Student','user_id','id');
    }

    public function Staff(){
        return $this->hasOne('App\Model\Staff','user_id','id');
    }

    public function Faculty(){
        return $this->belongsTo('App\Model\Faculty','faculty_id','id');
    }

    public function hasPermission(Permission $permission){
        //contains dung để kiểm tra xem nó có chứa permission k
        // ep kiểu boolean
        // optional là khi mà trong Role k có Permission thì ucngx k báo lỗi
        return !! optional(optional($this->Role)->Permissions)->contains($permission);
    }
}
