<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FileImport extends Model
{
    protected $table = 'file_imports';

    protected $fillable = ['file_path', 'file_name', 'status','staff_id'];

    public function Staff(){
        return $this->belongsTo('App\Model\Staff','staff_id','id');
    }
}
