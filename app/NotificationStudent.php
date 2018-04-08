<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationStudent extends Model
{
    protected $table = 'notification_students';

    protected $fillable = ['student_id','notification_id'];

    public function Notification(){
        return $this->belongsTo('App\Notification','notification_id','id');
    }

    public function Student(){
        return $this->belongsTo('App\Student','student_id','user_id');
    }

}
