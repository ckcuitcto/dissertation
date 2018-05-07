<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['title','content','created_by'];

    public function Staff(){
        return $this->belongsTo('App\Model\Staff','created_by','id');
    }

    public function NotificationStudents(){
        return $this->hasMany('App\Model\NotificationStudent','notification_id','id');
    }
}
