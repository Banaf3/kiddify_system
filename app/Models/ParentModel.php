<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parent_models';
    protected $primaryKey = 'parent_id';

    protected $fillable = ['occupation', 'account_status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function family()
    {
        return $this->hasMany(Family::class, 'parent_id');
    }
}
