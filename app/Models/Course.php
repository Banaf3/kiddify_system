<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $primaryKey = 'CourseID';

    protected $fillable = [
        'teachersID', 'Title', 'description', 'Start_time',
        'end_time', 'days', 'T_name', 'maxStudent', 'StudentID'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teachersID');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'CourseID');
    }
}
