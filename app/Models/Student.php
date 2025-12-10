<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <-- add this
use App\Models\Course; // <-- add this
use App\Models\User;
use App\Models\StudentScore;
use App\Models\TeacherFeedback;
use App\Models\Family;

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

    // -----------------------------
    // Student courses relationship
    // -----------------------------
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'course_student',
            'studentID',
            'courseID'
        );
    }
}
