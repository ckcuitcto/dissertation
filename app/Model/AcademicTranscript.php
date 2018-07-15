<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AcademicTranscript extends Model
{
    protected $table = 'academic_transcripts';

    public $timestamps = false;

    protected $fillable = ['user_id','semester_id','class_id','score_ia','score_ib','score_ic','score_i','score_ii','score_iii','score_iv','score_v','note'];

    public function Student(){
        return $this->belongsTo('App\Model\Student','user_id','user_id');
    }

    public function Semester(){
        return $this->belongsTo('App\Model\Semester','semester_id','id');
    }

    public function Classes(){
        return $this->belongsTo('App\Model\Classes','class_id','id');
    }
}
