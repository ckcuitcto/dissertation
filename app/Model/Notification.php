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

    public function Users()
    {
        return $this->belongsToMany('App\Model\User', 'notification_users', 'notification_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}
