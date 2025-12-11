<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class TeacherController extends Controller
{
    /**
     * TEACHER DASHBOARD — LIST COURSES
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isTeacher()) {
            abort(403, 'Unauthorized access.');
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'Teacher profile not found.');
        }

        $courses = $teacher->courses()->with('students')->get();

        return view('Module2.teacher.index', compact('teacher', 'courses'));
    }

    /**
     * VIEW STUDENTS ENROLLED IN A COURSE
     */
    public function viewCourseStudents($courseID)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isTeacher()) {
            abort(403, 'Unauthorized access.');
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'Teacher profile not found.');
        }

        $course = $teacher->courses()->with('students')->findOrFail($courseID);

        return view('Module2.teacher.show', [
            'course' => $course,
            'students' => $course->students
        ]);
    }

    /**
     * VIEW TEACHER SCHEDULE
     */
    public function schedule()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isTeacher()) {
            abort(403, 'Unauthorized access.');
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'Teacher profile not found.');
        }

        $courses = $teacher->courses()->with('students')->get();

        return view('Module2.teacher.schedule', compact('teacher', 'courses'));
    }

    public function assessments($courseID)
{
     /** @var \App\Models\User $user */
     $user = Auth::user();
    if (!$user || !$user->isTeacher()) {
        abort(403, 'Unauthorized access.');
    }

    $teacher = $user->teacher;
    if (!$teacher) {
        abort(403, 'Teacher profile not found.');
    }

    $course = $teacher->courses()->findOrFail($courseID);

    // Fetch assessments here if you have them
    $assessments = $course->assessments ?? [];

    return view('Module2.teacher.assessments', compact('course', 'assessments'));
}

}
