<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['title','content','created_by'];

    public function Staff(){
        return $this->belongsTo('App\Staff','created_by','id');
    }

    public function NotificationStudents(){
        return $this->hasMany('App\NotificationStudent','notification_id','id');
    }
}
