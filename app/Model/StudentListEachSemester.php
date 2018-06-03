<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentListEachSemester extends Model
{
    protected $table = "student_list_each_semesters";

    protected $fillable = ['class_id', 'user_id','monitor_id','semester_id','staff_id'];

    public function Staff()
    {
        return $this->belongsTo('App\Model\Staff', 'staff_id', 'id');
    }

    public function Classes()
    {
        return $this->belongsTo('App\Model\Classes', 'class_id', 'id');
    }

    public function Student()
    {
        return $this->belongsTo('App\Model\Student', 'user_id', 'user_id');
    }

    public function Monitor()
    {
        return $this->belongsTo('App\Model\Student', 'monitor_id', 'user_id');
    }

    public function Semester()
    {
        return $this->belongsTo('App\Model\Semester', 'semester_id', 'user_id');
    }
}
