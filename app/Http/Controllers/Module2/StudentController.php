<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // ---------------------------------------------------------
    // STUDENT DASHBOARD — LIST OF COURSES STUDENT IS ENROLLED IN
    // ---------------------------------------------------------
    public function index($studentID)
    {
        $student = Student::findOrFail($studentID);

        // Student's enrolled courses
        $courses = $student->courses()->with('teacher')->get();

        return view('module2.student_dashboard', compact('student', 'courses'));
    }

    // ---------------------------------------------------------
    // VIEW SINGLE COURSE DETAILS
    // ---------------------------------------------------------
    public function viewCourse($courseID)
    {
        $course = Course::with('teacher', 'students')->findOrFail($courseID);

        return view('module2.student_course_detail', compact('course'));
    }
}
