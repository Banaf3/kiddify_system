<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    protected $primaryKey = 'score_id';

    protected $fillable = [
        'StudentID', 'AssessmentID', 'CourseID',
        'marks_obtained', 'total_marks', 'grading_status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'AssessmentID');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'CourseID');
    }
}
