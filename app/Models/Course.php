<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $primaryKey = 'CourseID';
    protected $fillable = [
        'teachersID', 'Title', 'description', 'Start_time', 'end_time', 'days', 'maxStudent'
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class, 'teachersID', 'teachersID');
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'course_student', 'course_id', 'student_id');
    }

    
    public function sections()
    {
        return $this->hasMany(Section::class, 'CourseID', 'CourseID');
    }
}
