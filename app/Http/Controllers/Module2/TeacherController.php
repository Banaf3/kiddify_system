<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    // ---------------------------------------------------------
    // TEACHER DASHBOARD (COURSES LIST)
    // ---------------------------------------------------------
    public function index($teacherID)
    {
        $teacher = Teacher::findOrFail($teacherID);
        $courses = Course::where('teachersID', $teacherID)->get();

        return view('module2.teacher_dashboard', compact('teacher', 'courses'));
    }

    // ---------------------------------------------------------
    // VIEW STUDENTS ENROLLED IN COURSE
    // ---------------------------------------------------------
    public function viewCourseStudents($courseID)
    {
        $course = Course::findOrFail($courseID);
        $students = $course->students;

        return view('module2.teacher_course_students', compact('course', 'students'));
    }

    // ---------------------------------------------------------
    // VIEW TEACHER SCHEDULE (ALL COURSE SESSIONS)
    // ---------------------------------------------------------
    public function schedule($teacherID)
    {
        $teacher = Teacher::findOrFail($teacherID);

        // All courses taught by the teacher
        $courses = Course::where('teachersID', $teacherID)->get();

        return view('module2.teacher_schedule', compact('teacher', 'courses'));
    }
}
