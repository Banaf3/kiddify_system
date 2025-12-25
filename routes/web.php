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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpCodeMail;

Route::get('/', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return view('home');
})->name('home');

// Debug Mail Configuration Endpoint (Local Environment Only)
Route::get('/debug/mail-config', function () {
    // Only allow in local environment
    if (!app()->environment('local')) {
        abort(404);
    }

    $mailer = config('mail.default');
    $smtpConfig = config('mail.mailers.smtp');
    $fromConfig = config('mail.from');

    return response()->json([
        'mail_configuration' => [
            'default_mailer' => $mailer,
            'smtp' => [
                'host' => $smtpConfig['host'] ?? null,
                'port' => $smtpConfig['port'] ?? null,
                'encryption' => $smtpConfig['encryption'] ?? null,
                'username' => $smtpConfig['username'] ?? null,
                'password_set' => !empty($smtpConfig['password']),
            ],
            'from' => [
                'address' => $fromConfig['address'] ?? null,
                'name' => $fromConfig['name'] ?? null,
            ],
        ],
        'environment' => [
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
        ],
        'warnings' => [
            'mailer_is_log' => $mailer === 'log' ? 'WARNING: Mailer is set to log, emails will not be sent!' : null,
            'mailer_is_array' => $mailer === 'array' ? 'WARNING: Mailer is set to array, emails will not be sent!' : null,
            'smtp_not_gmail' => (!empty($smtpConfig['host']) && $smtpConfig['host'] !== 'smtp.gmail.com') ? 'WARNING: SMTP host is not Gmail' : null,
            'default_from_address' => ($fromConfig['address'] ?? null) === 'hello@example.com' ? 'WARNING: Using default from address' : null,
        ],
        'instructions' => 'Update .env file with Gmail SMTP credentials and run: php artisan config:clear',
    ], 200, [], JSON_PRETTY_PRINT);
})->name('debug.mail.config');

// Debug Mail Test Endpoint (Local Environment Only)
Route::get('/debug/mail-test', function () {
    // Only allow in local environment
    if (config('app.env') !== 'local') {
        abort(404);
    }

    try {
        // Get test user (logged in user or first user)
        $user = Auth::check() ? Auth::user() : App\Models\User::first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No users found in database. Create a user first.'
            ], 404);
        }

        // Generate test OTP
        $testOtp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Log attempt
        Log::info('Debug mail test initiated', [
            'user_id' => $user->id,
            'email' => $user->email,
            'purpose' => 'debug_test',
            'env' => config('app.env'),
            'mail_mailer' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from' => config('mail.from.address')
        ]);

        // Send email
        Mail::to($user->email)->send(new OtpCodeMail($user, $testOtp, 'login'));

        Log::info('Debug mail test sent successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'otp' => $testOtp
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Test OTP email sent successfully!',
            'details' => [
                'recipient' => $user->email,
                'otp_code' => $testOtp,
                'purpose' => 'login',
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from' => config('mail.from.address'),
                'note' => 'Check your email inbox (and spam folder). This is a test OTP and will not work for actual login.'
            ]
        ], 200);

    } catch (\Exception $e) {
        Log::error('Debug mail test failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_email' => $user->email ?? 'unknown',
            'mail_config' => [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'username' => config('mail.mailers.smtp.username'),
                'from' => config('mail.from.address')
            ]
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to send test email',
            'error' => $e->getMessage(),
            'troubleshooting' => [
                'Check your .env file has correct Gmail SMTP credentials',
                'Make sure you are using a Gmail App Password (not regular password)',
                'Run: php artisan config:clear',
                'Run: php artisan gmail:test ' . ($user->email ?? 'your@email.com'),
                'Check logs: storage/logs/laravel.log',
                'Verify Gmail account allows "Less secure app access" or use App Password'
            ]
        ], 500);
    }
})->name('debug.mail.test');

Route::get('/dashboard', function () {
    $user = Auth::user();
    $selectedRole = session('selected_role');

    // Check if user has both teacher and parent roles
    $hasTeacher = App\Models\Teacher::where('user_id', $user->id)->exists();
    $hasParent = App\Models\ParentModel::where('user_id', $user->id)->exists();

    // If user has multiple roles and hasn't selected one yet, redirect to role selection
    if ($hasTeacher && $hasParent && !$selectedRole) {
        return redirect()->route('role.select');
    }

    // Redirect based on selected role or user's role
    if ($selectedRole === 'teacher' || (!$selectedRole && $user->role === 'teacher')) {
        return app(App\Http\Controllers\Module2\TeacherController::class)->dashboard();
    } elseif ($selectedRole === 'parent' || (!$selectedRole && $user->role === 'parent')) {
        return app(App\Http\Controllers\ParentController::class)->dashboard();
    } elseif ($user->role === 'student') {
        return app(App\Http\Controllers\Module2\StudentController::class)->dashboard();
    } elseif ($user->role === 'admin') {
        return app(App\Http\Controllers\AdminController::class)->dashboard();
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile password verification (first step)
    Route::get('/profile/verify', [ProfileController::class, 'showVerify'])->name('profile.verify.show');
    Route::post('/profile/verify', [ProfileController::class, 'verify'])->name('profile.verify');

    // Profile routes (protected by OTP after password verification)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('can:isAdmin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', function (Illuminate\Http\Request $request) {
            $query = App\Models\User::query()->with(['student', 'teacher', 'parentModel']);

            // Filter by role
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            // Filter by gender
            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }

            // Filter by status (for students, teachers, and parents)
            if ($request->filled('status')) {
                $status = $request->status;
                $query->where(function ($q) use ($status) {
                    $q->whereHas('student', function ($sq) use ($status) {
                        $sq->where('account_status', $status);
                    })
                        ->orWhereHas('teacher', function ($tq) use ($status) {
                            $tq->where('account_status', $status);
                        })
                        ->orWhereHas('parentModel', function ($pq) use ($status) {
                            $pq->where('account_status', $status);
                        });
                });
            }

            // Search by name, email, or phone
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                });
            }

            $users = $query->orderBy('created_at', 'desc')->get();
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
                'phone_number' => 'nullable|string|max:20',
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
    Route::prefix('teacher/module2')->middleware('auth')->group(function () {
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
        Route::get('timetable/download', [StudentController::class, 'downloadTimetable'])->name('student.module2.timetable.download');

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

    //dsipaly questions for students
// Show assessments for a course
    Route::get('/student/courses/{course}/assessments', [AssessmentController::class, 'showAssessments'])
        ->name('student.courses.assessments');

    // Show sections for a course
    Route::get('/student/courses/{course}/sections', [AssessmentController::class, 'showSections'])
        ->name('student.courses.sections');

    // Show questions for a specific section
    Route::get('/student/sections/{section}/questions', [AssessmentController::class, 'showQuestions'])
        ->name('student.sections.questions');

    // Submit answers (POST)
    Route::post('/student/sections/{section}/questions/submit', [App\Http\Controllers\ProgressController::class, 'submitAttempt'])
        ->middleware(['auth', 'can:isStudent'])
        ->name('student.sections.questions.submit');

    Route::get('/student/sections/{section}/review/{attempt?}', [App\Http\Controllers\ProgressController::class, 'reviewAttempt'])
        ->middleware(['auth', 'can:isStudent'])
        ->name('student.review-attempt');







    // Teacher Routes
    Route::middleware('can:isTeacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/courses', function () {
            return view('teacher.courses');
        })->name('courses');

        Route::get('/grading', [\App\Http\Controllers\ProgressController::class, 'teacherGrading'])->name('grading');
        Route::get('/grade-student/{student}/{section}', [\App\Http\Controllers\ProgressController::class, 'gradeStudent'])->name('grade-student');
    });

    // Student Routes
    Route::middleware('can:isStudent')->prefix('student')->name('student.')->group(function () {
        Route::get('/courses', function () {
            return view('student.courses');
        })->name('courses');

        Route::get('/assessments', [\App\Http\Controllers\AssessmentController::class, 'studentDashboardAssessments'])->name('assessments');

        Route::get('/results', [\App\Http\Controllers\ProgressController::class, 'studentResults'])->name('results');

        Route::get('/timetable', [App\Http\Controllers\Module2\StudentController::class, 'timetable'])->name('timetable');
        Route::get('/timetable/download', [App\Http\Controllers\Module2\StudentController::class, 'downloadTimetable'])->name('timetable.download');
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

        Route::get('/reports', [\App\Http\Controllers\ProgressController::class, 'parentReports'])->name('reports');
    });
});

// Admin Routes
Route::middleware('can:isAdmin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [\App\Http\Controllers\ProgressController::class, 'adminReports'])->name('reports');
});

// AI Chat Routes (Authenticated users only with rate limiting)
Route::middleware(['auth', 'throttle:ai-chat'])->prefix('ai')->name('ai.')->group(function () {
    Route::get('/chat/history', [\App\Http\Controllers\AiChatController::class, 'history'])->name('chat.history');
    Route::post('/chat', [\App\Http\Controllers\AiChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/clear', [\App\Http\Controllers\AiChatController::class, 'clearHistory'])->name('chat.clear');
});

require __DIR__ . '/auth.php';

