<?php

use App\Http\Controllers\ProfileController;
//Module 2 -- Course Registration
use App\Http\Controllers\Module2\CourseController;
use App\Http\Controllers\Module2\TeacherController;
use App\Http\Controllers\Module2\StudentController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AssessmentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('can:isAdmin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', function () {
            $users = App\Models\User::orderBy('created_at', 'desc')->get();
            return view('admin.users', compact('users'));
        })->name('users');

        Route::delete('/users/{id}', function ($id) {
            $user = App\Models\User::findOrFail($id);
            $user->delete();
            return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
        })->name('users.delete');

        Route::get('/users/{id}/edit', function ($id) {
            $user = App\Models\User::findOrFail($id);
            return view('admin.users-edit', compact('user'));
        })->name('users.edit');

        Route::put('/users/{id}', function (Illuminate\Http\Request $request, $id) {
            $user = App\Models\User::findOrFail($id);

            // Validation rules
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone_number' => 'required|string|max:20',
                'gender' => 'required|in:male,female',
                'address' => 'required|string',
                'date_of_birth' => 'required|date',
                'role' => 'required|in:admin,teacher,student,parent',
                'account_status' => 'nullable|in:active,inactive',
            ];

            // If activating an inactive student, require password
            if ($user->role == 'student' && $request->account_status == 'active') {
                $student = App\Models\Student::where('user_id', $user->id)->first();
                if ($student && $student->account_status == 'inactive') {
                    $rules['student_password'] = 'required|string|min:8';
                }
            }

            $request->validate($rules);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'role' => $request->role,
            ]);

            // Update student account status and password if user is a student
            if ($user->role == 'student' && $request->has('account_status')) {
                $student = App\Models\Student::where('user_id', $user->id)->first();
                if ($student) {
                    // If activating from inactive status, update password
                    if ($request->account_status == 'active' && $student->account_status == 'inactive' && $request->filled('student_password')) {
                        $user->update(['password' => Hash::make($request->student_password)]);
                    }
                    $student->update(['account_status' => $request->account_status]);
                }
            }

            return redirect()->route('admin.users')->with('success', 'User updated successfully!');
        })->name('users.update');

        Route::get('/courses', function () {
            return view('admin.courses');
        })->name('courses');

        Route::get('/reports', function () {
            return view('admin.reports');
        })->name('reports');

        // Add Teacher Routes
        Route::get('/add-teacher', function () {
            return view('admin.add-teacher');
        })->name('add-teacher');

        Route::post('/add-teacher', function (Illuminate\Http\Request $request) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'phone_number' => 'required|string|max:20',
                'gender' => 'required|in:male,female',
                'date_of_birth' => 'required|date',
                'address' => 'required|string',
                'qualification' => 'required|string|max:30',
                'experience_years' => 'required|integer|min:0',
                'school_branch' => 'required|string|max:20',
            ]);

            // Create user
            $user = App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'role' => 'teacher',
            ]);

            // Create teacher record
            App\Models\Teacher::create([
                'UserID' => $user->id,
                'user_id' => $user->id,
                'qualification' => $request->qualification,
                'experience_years' => $request->experience_years,
                'school_branch' => $request->school_branch,
                'account_status' => 'active',
            ]);

            return redirect()->route('admin.add-teacher')->with('success', 'Teacher added successfully!');
        })->name('add-teacher.store');
    });
// ===============================
// MODULE 2 — ADMIN ROUTES
// ===============================
Route::prefix('admin/module2')->group(function () {
    Route::get('courses', [CourseController::class, 'index'])->name('admin.courses.index');
    Route::get('courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
    Route::post('courses', [CourseController::class, 'store'])->name('admin.courses.store');
    Route::get('courses/{id}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
    Route::put('courses/{id}', [CourseController::class, 'update'])->name('admin.courses.update');
    Route::delete('courses/{id}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');

    // Optional: view enrolled students
    Route::get('courses/{id}/students', [CourseController::class, 'viewStudents'])->name('admin.courses.students');
});

// ===============================
// MODULE 2 — TEACHER ROUTES
// ===============================
Route::prefix('teacher/module2')->middleware('auth')->group(function() {
    Route::get('courses', [TeacherController::class, 'index'])->name('teacher.courses.index');
    Route::get('courses/{courseID}', [TeacherController::class, 'viewCourseStudents'])->name('teacher.courses.show');
    Route::get('courses/{courseID}/assessments', [TeacherController::class, 'assessments'])->name('teacher.courses.assessments');
    Route::get('schedule', [TeacherController::class, 'schedule'])->name('teacher.schedule');
});



// ===============================
// MODULE 2 — STUDENT ROUTES
// ===============================
Route::prefix('student/module2')->middleware('auth')->group(function () {
    Route::get('courses', [StudentController::class, 'index'])->name('student.courses.index');
    Route::get('courses/{courseID}', [StudentController::class, 'viewCourse'])->name('student.courses.show');
    Route::get('timetable', [StudentController::class, 'timetable'])->name('student.courses.timetable');
Route::get('timetable/download', [StudentController::class, 'downloadTimetable'])->name('student.timetable.download');

});




    //section route
Route::middleware(['auth', 'can:isTeacher'])->group(function () {
    Route::get('/teacher/assessments', [SectionController::class, 'index'])->name('teacher.assessments');
    Route::post('/sectionsStore', [SectionController::class, 'store'])->name('sections.store');

    // Edit & Update
    Route::get('/sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
    Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');

    // Delete
    Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
});

// Show form to add question for a section
Route::get('/sections/{section}/questions/create', [AssessmentController::class, 'create'])
    ->name('teacher.add-questions')
    ->middleware(['auth', 'can:isTeacher']);

// Store new question
Route::post('/sections/{section}/questions', [AssessmentController::class, 'store'])
    ->name('questions.store')
    ->middleware(['auth', 'can:isTeacher']);

// Edit question
Route::get('/questions/{assessment}/edit', [AssessmentController::class, 'edit'])
    ->name('questions.edit')
    ->middleware(['auth', 'can:isTeacher']);

// Update question
Route::put('/questions/{assessment}', [AssessmentController::class, 'update'])
    ->name('questions.update')
    ->middleware(['auth', 'can:isTeacher']);

// Delete question
Route::delete('/questions/{assessment}', [AssessmentController::class, 'destroy'])
    ->name('questions.destroy')
    ->middleware(['auth', 'can:isTeacher']);

// Show sections for a specific course
Route::get('/courses/{course}/sections', [SectionController::class, 'showSections'])
    ->name('teacher.course.sections')
    ->middleware(['auth', 'can:isTeacher']);






    // Teacher Routes
    Route::middleware('can:isTeacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/courses', function () {
            return view('teacher.courses');
        })->name('courses');

        Route::get('/grading', function () {
            return view('teacher.grading');
        })->name('grading');
    });

    // Student Routes
    Route::middleware('can:isStudent')->prefix('student')->name('student.')->group(function () {
        Route::get('/courses', function () {
            return view('student.courses');
        })->name('courses');

        Route::get('/assessments', function () {
            return view('student.assessments');
        })->name('assessments');

        Route::get('/results', function () {
            return view('student.results');
        })->name('results');
    });

    // Parent Routes
    Route::middleware('can:isParent')->prefix('parent')->name('parent.')->group(function () {
        Route::get('/kids', function () {
            // Get all students where UserID matches the current parent's user ID
            $kids = App\Models\Student::where('UserID', Auth::user()->id)
                ->with('user') // Load the related user data
                ->get();

            return view('parent.kids', compact('kids'));
        })->name('kids');

        Route::get('/add-kid', function () {
            return view('parent.add-kid');
        })->name('add-kid');

        Route::post('/store-kid', function (Illuminate\Http\Request $request) {
            $request->validate([
                'kid_name' => 'required|string|max:255',
                'kid_email' => 'required|email|unique:users,email',
                'kid_phone' => 'nullable|string|max:20',
                'kid_gender' => 'required|in:male,female',
                'kid_dob' => 'required|date',
                'kid_address' => 'required|string',
            ]);

            // Create user account for the kid
            $user = App\Models\User::create([
                'name' => $request->kid_name,
                'email' => $request->kid_email,
                'password' => Hash::make('student123'),
                'role' => 'student',
                'phone_number' => $request->kid_phone ?? '',
                'gender' => $request->kid_gender,
                'date_of_birth' => $request->kid_dob,
                'address' => $request->kid_address,
            ]);

            // Create student record with parent's user ID
            App\Models\Student::create([
                'user_id' => $user->id,
                'UserID' => Auth::user()->id,
                'school_branch' => 'Main Branch',
                'class_name' => 'Not Assigned',
                'account_status' => 'inactive',
            ]);

            return redirect()->route('parent.kids')->with('success', 'Kid added successfully! Account pending admin approval.');
        })->name('store-kid');

        Route::get('/reports', function () {
            return view('parent.reports');
        })->name('reports');
    });
});

require __DIR__.'/auth.php';
