<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['name', 'date_time', 'image', 'CourseID'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'CourseID', 'CourseID');
    }

    public function assessments()
{
    return $this->hasMany(Assessment::class, 'SectionID', 'id');
}

}
