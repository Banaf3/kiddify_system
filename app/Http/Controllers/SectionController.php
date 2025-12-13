<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
    // Display all sections
    public function index()
    {
         $courses = Course::with('teacher')->get();
        return view('teacher.assessments', compact('courses'));

    }

    public function showSections(Course $course)
{
    // Fetch all sections for this course
    $sections = Section::where('CourseID', $course->CourseID)->get();

    return view('teacher.add-section', compact('course', 'sections'));
}


// Store a new section
    public function store(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string|max:50',
            'date_time' => 'required|date',
            'duration' => 'required|integer|min:1',
            'attempt_limit' => 'required|integer|min:1', // Added validation
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'course_id' => 'required|exists:courses,CourseID',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('sections', 'public');
        }

        Section::create([
            'name' => $request->section_name,
            'date_time' => $request->date_time,
            'duration' => $request->duration,
            'attempt_limit' => $request->attempt_limit, // Save attempt_limit
            'image' => $imageName,
            'CourseID' => $request->course_id,
        ]);

        return back()->with('success', 'Section added successfully!');
    }

    // Edit section
    public function edit(Section $section)
    {
        return view('teacher.edit_section', compact('section'));
    }

    // Update section
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'section_name' => 'required|string|max:50',
            'date_time' => 'required|date',
            'duration' => 'required|integer|min:1',
            'attempt_limit' => 'required|integer|min:1', // Added validation
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $section->image = $request->file('image')->store('sections', 'public');
        }

        $section->name = $request->section_name;
        $section->date_time = $request->date_time;
        $section->duration = $request->duration;
        $section->attempt_limit = $request->attempt_limit; // Update attempt_limit
        $section->save();

        return redirect()->route('teacher.assessments')->with('success', 'Section updated successfully!');
    }


public function destroy(Section $section)
{
    $section->delete();
   return back()->with('success', 'Section deleted successfully!');
}

}

