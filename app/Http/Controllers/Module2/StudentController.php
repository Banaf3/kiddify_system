<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Student;

class StudentController extends Controller
{
    /**
     * STUDENT DASHBOARD — LIST COURSES
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if the logged-in user is a student
        if (!$user || !$user->isStudent()) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        /** @var Student $student */
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        // Get all courses the student is enrolled in with teacher info
        $courses = $student->courses()->with('teacher')->get();

        return view('Module2.student.index', compact('student', 'courses'));
    }

    /**
     * VIEW SINGLE COURSE DETAILS
     *
     * @param int $courseID
     * @return \Illuminate\View\View
     */
    public function viewCourse($courseID)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isStudent()) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        /** @var Student $student */
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        // Get course with teacher and enrolled students
        $course = Course::with('teacher', 'students')->findOrFail($courseID);

        // Ensure student is enrolled in the course
        if (!$course->students->contains($student->studentID)) {
            abort(403, 'You are not enrolled in this course.');
        }

        return view('Module2.student.show', compact('course'));
    }

    /**
     * VIEW STUDENT TIMETABLE
     *
     * @return \Illuminate\View\View
     */
    public function timetable()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isStudent()) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        /** @var Student $student */
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        // Get all enrolled courses with teacher info
        $courses = $student->courses()->with('teacher')->get();

        // Prepare days of the week
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('Module2.student.timetable', compact('courses', 'daysOfWeek'));
    }
}
