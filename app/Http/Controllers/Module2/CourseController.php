<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Student;

class CourseController extends Controller
{
    // ---------------------------------------------------------
    // SHOW ALL COURSES
    // ---------------------------------------------------------
    public function index()
    {
        $courses = Course::with('teacher')->get();
        return view('Module2.course_index', compact('courses'));
    }

    // ---------------------------------------------------------
    // SHOW CREATE FORM
    // ---------------------------------------------------------
    public function create()
    {
        $teachers = Teacher::all();
        $students = Student::all();
        return view('Module2.course_create', compact('teachers', 'students'));
    }

    // ---------------------------------------------------------
    // STORE NEW COURSE
    // ---------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            "Title" => "required|string|max:30",
            "teachersID" => "required|exists:teachers,teachersID",
            "Start_time" => "required",
            "end_time" => "required",
            "days" => "required|array",
            "student_ids" => "required|array",
        ]);

        $course = Course::create([
            "Title" => $request->Title,
            "teachersID" => $request->teachersID,
            "description" => $request->description,
            "Start_time" => $request->Start_time,
            "end_time" => $request->end_time,
            "days" => json_encode($request->days),
            "maxStudent" => $request->maxStudent,
        ]);

        $course->students()->sync($request->student_ids);

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course created successfully.');
    }

    // ---------------------------------------------------------
    // SHOW EDIT FORM
    // ---------------------------------------------------------
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $teachers = Teacher::all();
        $students = Student::all();
        $selectedStudents = $course->students->pluck('studentID')->toArray();
        $selectedDays = json_decode($course->days, true);

        return view('Module2.course_edit', compact(
            'course', 'teachers', 'students', 'selectedStudents', 'selectedDays'
        ));
    }

    // ---------------------------------------------------------
    // UPDATE COURSE
    // ---------------------------------------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            "Title" => "required|string|max:30",
            "teachersID" => "required|exists:teachers,teachersID",
            "Start_time" => "required",
            "end_time" => "required",
            "days" => "required|array",
            "student_ids" => "required|array",
        ]);

        $course = Course::findOrFail($id);

        $course->update([
            "Title" => $request->Title,
            "teachersID" => $request->teachersID,
            "description" => $request->description,
            "Start_time" => $request->Start_time,
            "end_time" => $request->end_time,
            "days" => json_encode($request->days),
            "maxStudent" => $request->maxStudent,
        ]);

        $course->students()->sync($request->student_ids);

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course updated successfully.');
    }

    // ---------------------------------------------------------
    // DELETE COURSE
    // ---------------------------------------------------------
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->students()->detach();
        $course->delete();

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course deleted.');
    }

    // ---------------------------------------------------------
    // VIEW STUDENTS IN COURSE
    // ---------------------------------------------------------
    public function viewStudents($id)
    {
        $course = Course::findOrFail($id);
        $students = $course->students;

        return view('Module2.course_students', compact('course', 'students'));
    }
}
