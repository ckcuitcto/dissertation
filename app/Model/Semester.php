<?php
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    //
    protected $table = 'semesters';

    protected $fillable = ['date_start','date_end','year_from','year_to','term','date_start_to_mark','date_end_to_mark','date_start_to_re_mark','date_end_to_re_mark','date_start_to_request_re_mark','date_end_to_request_re_mark'];

    public function Proofs(){
        return $this->hasMany('App\Model\Proof','semester_id','id');
    }

    public function EvaluationForm(){
        return $this->hasMany('App\Model\EvaluationForm','semester_id','id');
    }

    public function MarkTimes(){
        return $this->hasMany('App\Model\MarkTime','semester_id','id');
    }

    public function StudentListEachSemester(){
        return $this->hasMany('App\Model\StudentListEachSemester','semester_id','id');
    }

    public function FileImports(){
        return $this->hasMany('App\Model\FileImport','semester_id','id');
    }

    public function Disciplines(){
        return $this->hasMany('App\Model\Discipline','semester_id','id');
    }

    public function AcademicTranscripts(){
        return $this->hasMany('App\Model\AcademicTranscript','semester_id','id');
    }
}
