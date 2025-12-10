<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Student;

class StudentController extends Controller
{
    // -----------------------------
    // STUDENT DASHBOARD — LIST COURSES
    // -----------------------------
    public function index()
    {
        /** @var Student $student */
        $student = Auth::guard('student')->user();

        if (!$student) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        $courses = $student->courses()->with('teacher')->get();

        return view('Module2.student.index', compact('student', 'courses'));
    }

    // -----------------------------
    // VIEW SINGLE COURSE DETAILS
    // -----------------------------
    public function viewCourse($courseID)
    {
        /** @var Student $student */
        $student = Auth::guard('student')->user();

        if (!$student) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        $course = Course::with('teacher', 'students')->findOrFail($courseID);

        // Ensure student is enrolled in the course
        if (!$course->students->contains($student->studentID)) {
            abort(403, 'You are not enrolled in this course.');
        }

        return view('Module2.student.show', compact('course'));
    }
}
