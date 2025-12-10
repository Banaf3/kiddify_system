<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class TeacherController extends Controller
{
    /**
     * -----------------------------
     * TEACHER DASHBOARD — LIST COURSES
     * -----------------------------
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user(); 

        if (!$teacher) {
            abort(403, 'Unauthorized access.');
        }

        // Get all courses taught by this teacher
        $courses = Course::where('teachersID', $teacher->teachersID)->get();

        return view('Module2.teacher.index', compact('teacher', 'courses'));
    }

    /**
     * -----------------------------
     * VIEW STUDENTS ENROLLED IN COURSE
     * -----------------------------
     */
    public function viewCourseStudents($courseID)
    {
        $teacher = Auth::guard('teacher')->user();

        if (!$teacher) {
            abort(403, 'Unauthorized access.');
        }

        $course = Course::findOrFail($courseID);

        // Ensure teacher only accesses their own course
        if ($course->teachersID !== $teacher->teachersID) {
            abort(403, 'You do not teach this course.');
        }

        $students = $course->students;

        return view('Module2.teacher.show', compact('course', 'students'));
    }

    /**
     * -----------------------------
     * VIEW TEACHER SCHEDULE
     * -----------------------------
     */
    public function schedule()
    {
        $teacher = Auth::guard('teacher')->user();

        if (!$teacher) {
            abort(403, 'Unauthorized access.');
        }

        $courses = Course::where('teachersID', $teacher->teachersID)->get();

        return view('Module2.teacher.schedule', compact('teacher', 'courses'));
    }
}
