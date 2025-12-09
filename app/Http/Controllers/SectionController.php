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
}

