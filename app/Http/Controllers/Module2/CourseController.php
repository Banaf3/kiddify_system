<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Student;

class CourseController extends Controller
{
    // List all courses
    public function index()
    {
        $courses = Course::with('teacher', 'students')->get();
        return view('module2.courses.index', compact('courses'));
    }

    // Show form to create a new course
    public function create()
    {
        $teachers = Teacher::all();
        $students = Student::all();
        return view('module2.courses.create', compact('teachers', 'students'));
    }

    // Store new course
    public function store(Request $request)
    {
        $request->validate([
            'Title' => 'required|string|max:30',
            'teachersID' => 'required|exists:teachers,teachersID',
            'Start_time' => 'required',
            'end_time' => 'required',
            'days' => 'required|array',
            'student_ids' => 'required|array',
        ]);

        $course = Course::create([
            'Title' => $request->Title,
            'teachersID' => $request->teachersID,
            'description' => $request->description,
            'Start_time' => $request->Start_time,
            'end_time' => $request->end_time,
            'days' => json_encode($request->days),
            'maxStudent' => $request->maxStudent,
        ]);

        $course->students()->sync($request->student_ids);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    // Show form to edit existing course
    public function edit($id)
    {
        $course = Course::with('students')->findOrFail($id);
        $teachers = Teacher::all();
        $students = Student::all();
        return view('module2.courses.edit', compact('course', 'teachers', 'students'));
    }

    // Update existing course
    public function update(Request $request, $id)
    {
        $request->validate([
            'Title' => 'required|string|max:30',
            'teachersID' => 'required|exists:teachers,teachersID',
            'Start_time' => 'required',
            'end_time' => 'required',
            'days' => 'required|array',
            'student_ids' => 'required|array',
        ]);

        $course = Course::findOrFail($id);

        $course->update([
            'Title' => $request->Title,
            'teachersID' => $request->teachersID,
            'description' => $request->description,
            'Start_time' => $request->Start_time,
            'end_time' => $request->end_time,
            'days' => json_encode($request->days),
            'maxStudent' => $request->maxStudent,
        ]);

        $course->students()->sync($request->student_ids);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    // Delete a course
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->students()->detach(); // remove pivot table relations
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
