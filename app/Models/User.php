<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'gender',
        'address',
        'date_of_birth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher(): bool
{
    // Check if selected role in session is teacher
    if (session('selected_role') === 'teacher') {
        return true;
    }
    // If no selected role, check if user has teacher record
    if (!session('selected_role')) {
        return $this->teacher()->exists() || $this->role === 'teacher';
    }
    return false;
}


    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is a parent
     */
    public function isParent(): bool
    {
        // Check if selected role in session is parent
        if (session('selected_role') === 'parent') {
            return true;
        }
        // If no selected role, check if user has parent record
        if (!session('selected_role')) {
            return $this->parentModel()->exists() || $this->role === 'parent';
        }
        return false;
    }

    /**
     * Get the student record associated with the user
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function parentModel()
    {
        return $this->hasOne(ParentModel::class, 'user_id');
    }
}
