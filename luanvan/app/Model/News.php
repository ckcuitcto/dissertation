<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = ['title','content','created_by','faculty_id'];

    public function Staff(){
        return $this->belongsTo('App\Model\Staff','created_by','id');
    }

    public function Faculty(){
        return $this->belongsTo('App\Model\Faculty','faculty_id','id');
    }
}
