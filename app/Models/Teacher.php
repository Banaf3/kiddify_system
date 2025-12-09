<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'teachersID';

    protected $fillable = [
        'user_id', 'UserID', 'qualification', 'experience_years',
        'school_branch', 'account_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'teachersID');
    }
}

