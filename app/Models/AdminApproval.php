<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminApproval extends Model
{
    protected $table = 'admin_approval';
    protected $primaryKey = 'Approval_id';

    protected $fillable = ['user_id', 'status', 'remarks'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

