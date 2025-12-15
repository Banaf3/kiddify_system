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

    /**
     * TEACHER DASHBOARD - OVERVIEW
     */
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user || !$user->isTeacher()) {
            abort(403, 'Unauthorized access.');
        }

        $teacher = $user->teacher;
        if (!$teacher) {
            abort(403, 'Teacher profile not found.');
        }

        // Stats
        $courses = $teacher->courses;
        $activeCoursesCount = $courses->count();

        // Total students enrolled in all courses (unique students)
        $totalStudents = $courses->flatMap(function ($course) {
            return $course->students;
        })->unique('studentID')->count();

        // Calculate pending grading tasks (manual simplified check)
        // Find sections in my courses where grade_visible is false AND there are attempts
        $pendingGradingCount = 0;
        foreach ($courses as $course) {
            foreach ($course->sections as $section) {
                $hasAttempts = \Illuminate\Support\Facades\DB::table('student_attempts')
                    ->where('section_id', $section->id)
                    ->exists();

                // If there are attempts but grades are not finalized/visible, consider it pending attention
                // Or purely based on if teacher needs to grade (if we had a specific 'graded' flag per attempt)
                // For now, let's use: section has attempts and review is NOT enabled (implying grading might be ongoing)

                if ($hasAttempts && !$section->grade_visible) {
                    $pendingGradingCount++;
                }
            }
        }

        return view('teacher.dashboard', compact('teacher', 'activeCoursesCount', 'totalStudents', 'pendingGradingCount'));
    }
}
