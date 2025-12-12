<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
   public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],

        'role' => ['required', 'in:admin,teacher,student,parent'],
        'phone_number' => ['required', 'string', 'max:20'],
        'gender' => ['required', 'in:male,female'],
        'address' => ['required', 'string', 'max:500'],
        'date_of_birth' => ['required', 'date', 'before_or_equal:2000-12-31'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),

        'role' => $request->role,
        'phone_number' => $request->phone_number,
        'gender' => $request->gender,
        'address' => $request->address,
        'date_of_birth' => $request->date_of_birth,
    ]);

    // Create teacher record if role is teacher
    if ($request->role === 'teacher') {
        \App\Models\Teacher::create([
            'user_id' => $user->id,
            'UserID' => $user->id,
            'qualification' => 'Not Specified',
            'experience_years' => 0,
            'school_branch' => 'Main Branch',
            'account_status' => 'active',
        ]);
    }

    // Create student record if role is student
    if ($request->role === 'student') {
        \App\Models\Student::create([
            'user_id' => $user->id,
            'UserID' => null,
            'school_branch' => 'Main Branch',
            'class_name' => 'Not Assigned',
            'account_status' => 'active',
        ]);
    }

    // Create parent record if role is parent
    if ($request->role === 'parent') {
        \App\Models\ParentModel::create([
            'user_id' => $user->id,
            'occupation' => 'Not Specified',
            'account_status' => 'active',
        ]);
    }

    event(new Registered($user));

    return redirect()->route('login')->with('status', 'Registration successful! Please login.');
}

}
