<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherFeedback extends Model
{
    protected $table = 'teacher_feedback';
    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'StudentID', 'CourseID', 'teachersID', 'comments'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'CourseID');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teachersID');
    }
}
