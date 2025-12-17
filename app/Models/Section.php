<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Section.php
class Section extends Model
{
    protected $fillable = ['name', 'date_time', 'image', 'CourseID', 'duration', 'attempt_limit', 'review_enabled', 'grade_visible'];

    protected $casts = [
        'review_enabled' => 'boolean',
        'grade_visible' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'CourseID', 'CourseID');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'SectionID', 'id');
    }

    // Attempts USED
    public function attemptsUsed()
    {
        $student = \App\Models\Student::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        if (!$student)
            return 0;

        return \Illuminate\Support\Facades\DB::table('student_attempts')
            ->where('student_id', $student->studentID)
            ->where('section_id', $this->id)
            ->distinct('attempt_number')
            ->count('attempt_number');
    }

    // Attempts LEFT
    public function attemptsLeft()
    {
        return max(0, $this->attempt_limit - $this->attemptsUsed());
    }

    public function canAttempt()
    {
        return $this->attemptsLeft() > 0;
    }

    // Increase attempt count (Removed as this should be handled by controller logic, not model getter)
    // But kept here as placeholder or deprecated if needed, though logic should be in controller.
    // For now, removing the session-based markAttempt as it's misleading.

    public function status()
    {
        $used = $this->attemptsUsed();
        if ($used >= $this->attempt_limit)
            return 'Completed';
        if ($used > 0)
            return 'Taken';
        return 'Not Started';
    }
}
