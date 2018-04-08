<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ['title','content','created_by'];

    public function Student(){
        return $this->belongsTo('App\Student','created_by','user_id');
    }


}
