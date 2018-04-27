<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NotificationStudent extends Model
{
    protected $table = 'notification_students';

    protected $fillable = ['student_id','notification_id'];

    public function Notification(){
        return $this->belongsTo('App\Model\Notification','notification_id','id');
    }

    public function Student(){
        return $this->belongsTo('App\Model\Student','student_id','id');
    }

}
