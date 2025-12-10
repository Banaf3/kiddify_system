<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class StudentController extends Controller
{
    // ---------------------------------------------------------
    // STUDENT DASHBOARD — LIST OF COURSES STUDENT IS ENROLLED IN
    // ---------------------------------------------------------
    public function index()
    {
        $student = Auth::user(); // assumes Student model is used for auth
        $courses = $student->courses()->with('teacher')->get();

        return view('Module2.student_dashboard', compact('student', 'courses'));
    }

    // ---------------------------------------------------------
    // VIEW SINGLE COURSE DETAILS
    // ---------------------------------------------------------
    public function viewCourse($courseID)
    {
        $student = Auth::user();
        $course = Course::with('teacher', 'students')->findOrFail($courseID);

        // Ensure student is enrolled
        if (!$course->students->contains($student->id)) {
            abort(403, 'You are not enrolled in this course.');
        }

        return view('Module2.student_course_detail', compact('course'));
    }
}
