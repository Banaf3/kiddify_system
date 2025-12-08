<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $primaryKey = 'AssessmentID';

    protected $fillable = ['question', 'grade', 'answer', 'CourseID'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'CourseID');
    }
}

