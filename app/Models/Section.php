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
        $attempts = session()->get('section_attempts', []);
        return $attempts[$this->id] ?? 0;
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

    // Increase attempt count
    public function markAttempt()
    {
        $attempts = session()->get('section_attempts', []);
        $attempts[$this->id] = ($attempts[$this->id] ?? 0) + 1;
        session()->put('section_attempts', $attempts);
    }

    public function status()
    {
        return $this->attemptsUsed() > 0 ? 'Taken' : 'Not Taken';
    }
}
