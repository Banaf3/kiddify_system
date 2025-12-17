<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Section;
use App\Models\Course;


class AssessmentController extends Controller
{
    // Show the form to add a new question for a specific section
    public function create(Section $section)
    {
        // Get all questions for this section
        $questions = $section->assessments()->get(); // assuming you have a relationship in Section model

        return view('teacher.add-questions', compact('section', 'questions'));
    }


    public function store(Request $request, Section $section)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'optionA' => 'required|string|max:255',
            'optionB' => 'required|string|max:255',
            'optionC' => 'required|string|max:255',
            'correct_answer' => 'required|string|in:A,B,C',
            'grade' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle optional image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('questions', 'public');
        }

        Assessment::create([
            'question' => $validated['question'],
            'optionA' => $validated['optionA'],
            'optionB' => $validated['optionB'],
            'optionC' => $validated['optionC'],
            'correct_answer' => $validated['correct_answer'],
            'grade' => $validated['grade'],
            'CourseID' => $section->CourseID,
            'SectionID' => $section->id,
            'image' => $validated['image'] ?? null,
        ]);

        return redirect()
            ->route('teacher.add-questions', $section->id)
            ->with('success', 'Question added successfully!');
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

    // Show sections for a course
    public function showSections(Course $course)
    {
        $sections = $course->sections()->get();
        return view('Module3.sections', compact('course', 'sections'));
    }

    public function showQuestions(Section $section)
    {
        //  Block if no attempts left
        if (!$section->canAttempt()) {
            return redirect()
                ->route('student.courses.sections', $section->CourseID)
                ->with('error', 'You have no attempts left for this assessment.');
        }

        // Removed markAttempt call as it was session based. 
        // Real attempt is recorded upon submission.

        $assessments = $section->assessments()->get();
        $course = $section->course;

        return view('Module3.questions', compact('section', 'assessments', 'course'));
    }

    // Handle submitted answers
    public function submitAnswers(Request $request, Section $section)
    {
        $answers = $request->input('answers'); // [assessment_id => option]

        // Calculate grade
        $grade = 0;
        if ($answers) {
            foreach ($answers as $id => $answer) {
                $assessment = Assessment::find($id);
                if ($assessment && $assessment->correct_answer === $answer) {
                    $grade += $assessment->grade;
                }
            }
        }

        // Record attempt in database
        $student = \App\Models\Student::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        if ($student) {
            // Calculate next attempt number
            $currentAttempts = \Illuminate\Support\Facades\DB::table('student_attempts')
                ->where('student_id', $student->studentID)
                ->where('section_id', $section->id)
                ->max('attempt_number') ?? 0;

            $attemptNumber = $currentAttempts + 1;

            \Illuminate\Support\Facades\DB::table('student_attempts')->insert([
                'student_id' => $student->studentID,
                'section_id' => $section->id,
                'attempt_number' => $attemptNumber,
                'marks_obtained' => $grade,
                'total_marks' => $section->assessments->sum('grade'), // Store total to avoid recalc
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('student.courses.sections', $section->CourseID)
            ->with('success', "Your answers have been submitted! Grade: $grade");
    }

    // Show assessments (sections) for a course
    public function showAssessments(Course $course)
    {
        $sections = $course->sections()->get();
        return view('student.assessments', compact('course', 'sections'));
    }




    /**
     * Display all assessments for the student's enrolled courses.
     */
    public function studentDashboardAssessments()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $student = \App\Models\Student::where('user_id', $user->id)->firstOrFail();

        // Get enrolled courses with their sections and assessments
        $courses = $student->courses()->with(['sections.assessments'])->get();

        return view('student.assessments', compact('courses'));
    }
}
