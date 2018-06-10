<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ['title','content','reply','created_by'];

    public function Student(){
        return $this->belongsTo('App\Model\Student','created_by','id');
    }

}
