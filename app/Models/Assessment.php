<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    // Set primary key
    protected $primaryKey = 'AssessmentID';

    // Allow mass assignment for these columns
    protected $fillable = [
        'question',
        'grade',
        'optionA',
        'optionB',
        'optionC',
        'CourseID',
        'SectionID',
        'correct_answer'
    ];

    // Relation to Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'CourseID');
    }

    // Relation to Section
    public function section()
    {
        return $this->belongsTo(Section::class, 'SectionID');
    }
}
