<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
    // Display all sections
    public function index()
    {
        $sections = Section::all(); // fetch all sections
        return view('teacher.assessments', compact('sections'));
    }


    // Store a new section
    public function store(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string|max:50',
            'date_time' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('sections', 'public');
        }

        Section::create([
            'name' => $request->section_name,
            'date_time' => $request->date_time,
            'image' => $imageName,
        ]);

        return redirect()->route('teacher.assessments')
                         ->with('success', 'Section added successfully!');
    }

    public function edit(Section $section)
{
    return view('teacher.edit_section', compact('section'));
}

public function update(Request $request, Section $section)
{
    $request->validate([
        'section_name' => 'required|string|max:50',
        'date_time' => 'required|date',
        'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $section->image = $request->file('image')->store('sections', 'public');
    }

    $section->name = $request->section_name;
    $section->date_time = $request->date_time;
    $section->save();

    return redirect()->route('teacher.assessments')->with('success', 'Section updated successfully!');
}

public function destroy(Section $section)
{
    $section->delete();
    return redirect()->route('teacher.assessments')->with('success', 'Section deleted successfully!');
}

}

