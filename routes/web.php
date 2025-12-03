<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
            return view('admin.users');
        })->name('users');
        
        Route::get('/courses', function () {
            return view('admin.courses');
        })->name('courses');
        
        Route::get('/reports', function () {
            return view('admin.reports');
        })->name('reports');
    });

    // Teacher Routes
    Route::middleware('can:isTeacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/courses', function () {
            return view('teacher.courses');
        })->name('courses');
        
        Route::get('/assessments', function () {
            return view('teacher.assessments');
        })->name('assessments');
        
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
            return view('parent.kids');
        })->name('kids');
        
        Route::get('/reports', function () {
            return view('parent.reports');
        })->name('reports');
    });
});

require __DIR__.'/auth.php';
