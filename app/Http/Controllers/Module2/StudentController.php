<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Student;

class StudentController extends Controller
{
    // ---------------------------------------------------------
    // STUDENT DASHBOARD — LIST OF COURSES STUDENT IS ENROLLED IN
    // ---------------------------------------------------------
    public function index()
    {
        /** @var Student $student */
        $student = Auth::guard('student')->user(); // use student guard

        if (!$student) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        $courses = $student->courses()->with('teacher')->get();

        return view('Module2.student_dashboard', compact('student', 'courses'));
    }

    // ---------------------------------------------------------
    // VIEW SINGLE COURSE DETAILS
    // ---------------------------------------------------------
    public function viewCourse($courseID)
    {
        /** @var Student $student */
        $student = Auth::guard('student')->user();

        if (!$student) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        $course = Course::with('teacher', 'students')->findOrFail($courseID);

        // Optional: check enrollment
        if (!$course->students->contains($student->studentID)) {
            abort(403, 'You are not enrolled in this course.');
        }

        return view('Module2.student_course_detail', compact('course'));
    }
}
