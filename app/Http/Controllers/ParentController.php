<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    /**
     * PARENT DASHBOARD
     */
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user || !$user->isParent()) {
            abort(403, 'Unauthorized access.');
        }

        // Get Children linked to this parent (UserID in students table links to parent's User ID)
        $children = Student::where('UserID', $user->id)
            ->with(['user', 'courses.sections']) // Eager load user and courses
            ->get();

        // Calculate stats for each child
        foreach ($children as $child) {
            $child->enrolled_courses_count = $child->courses->count();

            // Calculate Average Grade
            $attempts = DB::table('student_attempts')
                ->join('sections', 'student_attempts.section_id', '=', 'sections.id')
                ->where('student_attempts.student_id', $child->studentID)
                ->where('sections.grade_visible', true)
                ->select('student_attempts.marks_obtained', 'student_attempts.section_id')
                ->get();

            $totalPercentage = 0;
            $gradedCount = 0;

            // Group by section to get highest score per section
            $bestScores = $attempts->groupBy('section_id')->map(function ($sectionAttempts) {
                return $sectionAttempts->max('marks_obtained');
            });

            foreach ($bestScores as $sectionId => $score) {
                $totalMarks = \App\Models\Assessment::where('SectionID', $sectionId)->sum('grade');
                if ($totalMarks > 0) {
                    $totalPercentage += ($score / $totalMarks) * 100;
                    $gradedCount++;
                }
            }

            $child->average_grade = $gradedCount > 0 ? round($totalPercentage / $gradedCount, 1) : 0;
        }

        return view('parent.dashboard', compact('children'));
    }
}
