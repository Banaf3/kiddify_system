<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Section;

class AssessmentController extends Controller
{
   // Show the form to add a new question for a specific section
public function create(Section $section)
{
    // Get all questions for this section
    $questions = $section->assessments()->get(); // assuming you have a relationship in Section model

    return view('teacher.add-questions', compact('section', 'questions'));
}


    // Store a new question
  
public function store(Request $request, Section $section)
{
    $request->validate([
        'question' => 'required|string|max:255',
        'optionA' => 'required|string|max:255',
        'optionB' => 'required|string|max:255',
        'optionC' => 'required|string|max:255',
        'correct_answer' => 'required|string|in:A,B,C',
        'grade' => 'required|integer|min:0',
    ]);

    try {
        Assessment::create([
            'question' => $request->question,
            'optionA' => $request->optionA,
            'optionB' => $request->optionB,
            'optionC' => $request->optionC,
            'correct_answer' => $request->correct_answer,
            'grade' => $request->grade,
            'CourseID' => $section->CourseID,
            'SectionID' => $section->id,
        ]);

        return redirect()->route('teacher.add-questions', $section->id)
                         ->with('success', 'Question added successfully!');

    } catch(QueryException $e) {
        return back()->withErrors(['db_error' => $e->getMessage()]);
    }
}


    // Show the form to edit a question
    public function edit(Assessment $assessment)
    {
        return view('teacher.edit-question', compact('assessment'));
    }

    // Update an existing question
    public function update(Request $request, Assessment $assessment)
    {
        $request->validate([
            'question' => 'required|string|max:100',
            'grade' => 'required|integer|min:0',
            'optionA' => 'required|string|max:100',
            'optionB' => 'required|string|max:100',
            'optionC' => 'required|string|max:100',
        ]);

        $assessment->update($request->only('question', 'grade', 'optionA', 'optionB', 'optionC'));

        return redirect()->route('teacher.add-questions', $assessment->SectionID)
                         ->with('success', 'Question updated successfully!');
    }

    // Delete a question
    public function destroy(Assessment $assessment)
    {
        $sectionId = $assessment->SectionID;
        $assessment->delete();

        return redirect()->route('teacher.add-questions', $sectionId)
                         ->with('success', 'Question deleted successfully!');
    }
}
