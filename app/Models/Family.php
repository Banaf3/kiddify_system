<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $primaryKey = 'FamilyID';

    protected $fillable = [
        'parent_id', 'StudentID', 'relationship_type'
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID');
    }
}

