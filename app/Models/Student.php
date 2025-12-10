<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Student extends Model
{
    protected $primaryKey = 'studentID';

    protected $fillable = [
        'user_id', 'UserID', 'school_branch', 'class_name', 'account_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scores()
    {
        return $this->hasMany(StudentScore::class, 'StudentID');
    }

    public function feedback()
    {
        return $this->hasMany(TeacherFeedback::class, 'StudentID');
    }

    public function family()
    {
        return $this->hasMany(Family::class, 'StudentID');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id');
    }
}

