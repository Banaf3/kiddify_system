<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProgressController extends Controller
{
    /**
     * Submit assessment attempt with auto-grading
     */
    public function submitAttempt(Request $request, Section $section)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string|in:A,B,C'
        ]);

        $student = Student::where('user_id', Auth::id())->firstOrFail();

        try {
            DB::beginTransaction();

            // Get current attempt number for this section
            $currentAttempt = DB::table('student_attempts')
                ->where('student_id', $student->studentID)
                ->where('section_id', $section->id)
                ->max('attempt_number') ?? 0;

            $newAttemptNumber = $currentAttempt + 1;

            // Check attempt limit
            if ($section->attempt_limit && $newAttemptNumber > $section->attempt_limit) {
                return back()->with('error', 'You have reached the maximum number of attempts for this section.');
            }

            $totalMarks = 0;
            $marksObtained = 0;

            // Process each answer with auto-grading
            foreach ($request->answers as $assessmentId => $selectedAnswer) {
                $assessment = Assessment::findOrFail($assessmentId);
                $isCorrect = ($selectedAnswer === $assessment->correct_answer);
                $marks = $isCorrect ? $assessment->grade : 0;

                $marksObtained += $marks;
                $totalMarks += $assessment->grade;

                // Store attempt
                DB::table('student_attempts')->insert([
                    'student_id' => $student->studentID,
                    'assessment_id' => $assessmentId,
                    'section_id' => $section->id,
                    'selected_answer' => $selectedAnswer,
                    'is_correct' => $isCorrect,
                    'marks_obtained' => $marks,
                    'attempt_number' => $newAttemptNumber,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Get highest score across all attempts for this section
            $highestScore = $this->calculateHighestScore($student->studentID, $section->id);

            // Update or create student score record
            StudentScore::updateOrCreate(
                [
                    'StudentID' => $student->studentID,
                    'CourseID' => $section->CourseID,
                ],
                [
                    'AssessmentID' => $section->assessments->first()->AssessmentID ?? null,
                    'marks_obtained' => $highestScore['marks'],
                    'total_marks' => $highestScore['total'],
                    'grading_status' => 'Graded'
                ]
            );

            DB::commit();

            // Customize success message based on grade visibility
            if ($section->grade_visible) {
                $message = "Assessment submitted! Score: {$marksObtained}/{$totalMarks}. Your highest score: {$highestScore['marks']}/{$highestScore['total']}";
            } else {
                $message = "Assessment submitted successfully! Your teacher will review and grade it soon.";
            }

            return redirect()
                ->route('student.courses.sections', $section->CourseID)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Assessment submission failed', [
                'student_id' => $student->studentID,
                'section_id' => $section->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to submit assessment. Please try again.');
        }
    }

    /**
     * Calculate highest score from all attempts
     */
    private function calculateHighestScore($studentId, $sectionId)
    {
        $attempts = DB::table('student_attempts')
            ->where('student_id', $studentId)
            ->where('section_id', $sectionId)
            ->select('attempt_number', DB::raw('SUM(marks_obtained) as total_marks'))
            ->groupBy('attempt_number')
            ->get();

        $highestMarks = $attempts->max('total_marks') ?? 0;

        $totalMarks = Assessment::where('SectionID', $sectionId)->sum('grade');

        return [
            'marks' => $highestMarks,
            'total' => $totalMarks
        ];
    }

    /**
     * View student's attempt for review
     */
    public function reviewAttempt(Section $section, $attemptNumber = null)
    {
        $this->authorize('review', $section);

        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // Get latest attempt if not specified
        if (!$attemptNumber) {
            $attemptNumber = DB::table('student_attempts')
                ->where('student_id', $student->studentID)
                ->where('section_id', $section->id)
                ->max('attempt_number');
        }

        if (!$attemptNumber) {
            return back()->with('error', 'No attempts found for this section.');
        }

        // Get all attempts for this section
        $attempts = DB::table('student_attempts')
            ->where('student_id', $student->studentID)
            ->where('section_id', $section->id)
            ->where('attempt_number', $attemptNumber)
            ->get();

        // Load assessments with student answers
        $assessments = Assessment::where('SectionID', $section->id)->get()->map(function ($assessment) use ($attempts) {
            $attempt = $attempts->firstWhere('assessment_id', $assessment->AssessmentID);
            $assessment->student_answer = $attempt->selected_answer ?? null;
            $assessment->was_correct = $attempt->is_correct ?? false;
            $assessment->marks_earned = $attempt->marks_obtained ?? 0;
            return $assessment;
        });

        $totalMarks = $assessments->sum('grade');
        $marksObtained = $assessments->sum('marks_earned');

        // Get all attempt numbers for navigation
        $allAttempts = DB::table('student_attempts')
            ->where('student_id', $student->studentID)
            ->where('section_id', $section->id)
            ->distinct()
            ->pluck('attempt_number');

        // Determine back URL based on referrer
        $backUrl = request()->headers->get('referer');
        $backText = 'Back to Sections';

        if ($backUrl && str_contains($backUrl, 'student/results')) {
            // From results page
            $backUrl = route('student.results');
            $backText = 'Back to Results';
        } elseif ($backUrl && str_contains($backUrl, 'student/assessments')) {
            // From assessments page
            $backUrl = route('student.assessments');
            $backText = 'Back to Assessments';
        } else {
            // Default to sections page
            $backUrl = route('student.courses.sections', $section->course);
        }

        return view('student.review-assessment', compact(
            'section',
            'assessments',
            'totalMarks',
            'marksObtained',
            'attemptNumber',
            'allAttempts',
            'backUrl',
            'backText'
        ))->with('gradeVisible', $section->grade_visible);
    }

    /**
     * Teacher view: Manage student scores
     */
    public function teacherGrading(Request $request)
    {
        $this->authorize('isTeacher');

        // Get courses taught by this teacher
        $teacher = Auth::user()->teacher;
        $courses = $teacher->courses()->get();

        $selectedCourse = null;
        $sections = collect();
        $selectedSection = null;
        $studentsData = collect();

        // Auto-select if only one course
        if ($courses->count() === 1 && !$request->has('course_id')) {
            $selectedCourse = $courses->first();
        }
        // If course is selected
        elseif ($request->has('course_id') && $request->course_id) {
            $selectedCourse = $courses->firstWhere('CourseID', $request->course_id);
        }

        if ($selectedCourse) {
            $sections = $selectedCourse->sections;

            // Auto-select if only one section
            if ($sections->count() === 1 && !$request->has('section_id')) {
                $selectedSection = $sections->first();
            }
        }

        // If section is selected
        if ($request->has('section_id') && $request->section_id && $selectedCourse) {
            $selectedSection = $sections->firstWhere('id', $request->section_id);

            if ($selectedSection) {
                // Get all students enrolled in this course
                $students = $selectedCourse->students;

                // For each student, get their attempt data
                $studentsData = $students->map(function ($student) use ($selectedSection) {
                    $attempts = DB::table('student_attempts')
                        ->where('student_id', $student->studentID)
                        ->where('section_id', $selectedSection->id)
                        ->distinct()
                        ->count('attempt_number');

                    $highestScore = null;
                    $totalMarks = null;
                    $percentage = null;

                    if ($attempts > 0) {
                        $scores = DB::table('student_attempts')
                            ->where('student_id', $student->studentID)
                            ->where('section_id', $selectedSection->id)
                            ->select('attempt_number', DB::raw('SUM(marks_obtained) as total_marks'))
                            ->groupBy('attempt_number')
                            ->get();

                        $highestScore = $scores->max('total_marks') ?? 0;
                        $totalMarks = Assessment::where('SectionID', $selectedSection->id)->sum('grade');
                        $percentage = $totalMarks > 0 ? round(($highestScore / $totalMarks) * 100, 1) : 0;
                    }

                    return [
                        'student' => $student,
                        'attempts' => $attempts,
                        'highest_score' => $highestScore,
                        'total_marks' => $totalMarks,
                        'percentage' => $percentage
                    ];
                });
            }
        }

        return view('teacher.grading', compact(
            'courses',
            'selectedCourse',
            'sections',
            'selectedSection',
            'studentsData'
        ));
    }

    /**
     * Teacher view: Grade specific student
     */
    public function gradeStudent(Student $student, Section $section, Request $request)
    {
        $this->authorize('gradeStudent', $student);

        // Get attempt number from request or default to latest
        $attemptNumber = $request->get('attempt');

        if (!$attemptNumber) {
            $attemptNumber = DB::table('student_attempts')
                ->where('student_id', $student->studentID)
                ->where('section_id', $section->id)
                ->max('attempt_number');
        }

        if (!$attemptNumber) {
            return back()->with('error', 'No attempts found for this student.');
        }

        // Get all attempts for this section
        $attempts = DB::table('student_attempts')
            ->where('student_id', $student->studentID)
            ->where('section_id', $section->id)
            ->where('attempt_number', $attemptNumber)
            ->get();

        // Load assessments with student answers
        $assessments = Assessment::where('SectionID', $section->id)->get()->map(function ($assessment) use ($attempts) {
            $attempt = $attempts->firstWhere('assessment_id', $assessment->AssessmentID);
            $assessment->student_answer = $attempt->selected_answer ?? null;
            $assessment->was_correct = $attempt->is_correct ?? false;
            $assessment->marks_earned = $attempt->marks_obtained ?? 0;
            return $assessment;
        });

        $totalMarks = $assessments->sum('grade');
        $marksObtained = $assessments->sum('marks_earned');

        // Get all attempt numbers for navigation
        $allAttempts = DB::table('student_attempts')
            ->where('student_id', $student->studentID)
            ->where('section_id', $section->id)
            ->distinct()
            ->pluck('attempt_number');

        return view('teacher.grade-student', compact(
            'student',
            'section',
            'assessments',
            'totalMarks',
            'marksObtained',
            'attemptNumber',
            'allAttempts'
        ))->with([
                    'courseId' => $section->CourseID,
                    'sectionId' => $section->id
                ]);
    }

    /**
     * Student/Parent view progress
     */
    public function viewProgress($studentId = null)
    {
        $user = Auth::user();

        // Determine which student to show
        if ($user->role === 'Student') {
            $student = $user->student;
        } elseif ($user->role === 'Parent') {
            // Check authorization for parent-child link
            $this->authorize('viewProgress', [StudentScore::class, $studentId]);
            $student = Student::findOrFail($studentId);
        } else {
            abort(403);
        }

        // Get all scores
        $scores = StudentScore::where('StudentID', $student->studentID)
            ->with(['course', 'assessment'])
            ->get();

        // Get feedback
        $feedback = DB::table('teacher_feedback')
            ->where('StudentID', $student->studentID)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_assessments' => $scores->count(),
            'average_score' => $scores->avg(function ($score) {
                return $score->total_marks > 0
                    ? ($score->marks_obtained / $score->total_marks) * 100
                    : 0;
            }),
            'graded_count' => $scores->where('grading_status', 'Graded')->count(),
            'pending_count' => $scores->where('grading_status', 'Pending')->count()
        ];

        return view('progress.view-progress', compact('student', 'scores', 'feedback', 'stats'));
    }

    /**
     * Admin view: All students progress report
     */
    public function adminReports(Request $request)
    {
        $this->authorize('isAdmin');

        // Get filter parameters
        $courseId = $request->get('course_id');

        // Get all courses for filter
        $courses = \App\Models\Course::all();

        // Get all students with their progress
        $studentsQuery = Student::with('user');

        $studentsData = $studentsQuery->get()->map(function ($student) use ($courseId) {
            $query = DB::table('student_attempts')
                ->join('sections', 'student_attempts.section_id', '=', 'sections.id')
                ->where('student_attempts.student_id', $student->studentID);

            if ($courseId) {
                $query->where('sections.CourseID', $courseId);
            }

            $totalAttempts = $query->count();

            $sections = $query->distinct()->pluck('section_id');
            $totalAssessments = $sections->count();

            // Calculate average percentage across all assessments
            $avgPercentage = 0;
            if ($totalAssessments > 0) {
                $percentages = $sections->map(function ($sectionId) use ($student) {
                    $scores = DB::table('student_attempts')
                        ->where('student_id', $student->studentID)
                        ->where('section_id', $sectionId)
                        ->select('attempt_number', DB::raw('SUM(marks_obtained) as total_marks'))
                        ->groupBy('attempt_number')
                        ->get();

                    $highestScore = $scores->max('total_marks') ?? 0;
                    $totalMarks = Assessment::where('SectionID', $sectionId)->sum('grade');

                    return $totalMarks > 0 ? ($highestScore / $totalMarks) * 100 : 0;
                });

                $avgPercentage = round($percentages->avg(), 1);
            }

            return [
                'student' => $student,
                'total_attempts' => $totalAttempts,
                'assessments_taken' => $totalAssessments,
                'average_percentage' => $avgPercentage
            ];
        })->sortByDesc('average_percentage');

        return view('admin.reports', compact('studentsData', 'courses', 'courseId'));
    }

    /**
     * Parent view: Children progress report
     */
    public function parentReports()
    {
        $user = Auth::user();
        $parent = $user->parentModel;

        if (!$parent) {
            abort(403, 'Parent profile not found');
        }

        // Get all children of this parent
        // Get all children of this parent (using UserID as the foreign key to parent's user record)
        $children = Student::where('UserID', $user->id)->with('user')->get();

        $childrenData = $children->map(function ($student) {
            // Get all sections this student has attempted
            $progressData = DB::table('student_attempts')
                ->join('sections', 'student_attempts.section_id', '=', 'sections.id')
                ->join('courses', 'sections.CourseID', '=', 'courses.CourseID')
                ->where('student_attempts.student_id', $student->studentID)
                ->select(
                    'courses.Title as course_title',
                    'sections.name as section_name',
                    'student_attempts.section_id',
                    'sections.grade_visible',
                    DB::raw('COUNT(DISTINCT attempt_number) as total_attempts'),
                    DB::raw('MAX(student_attempts.created_at) as last_attempt')
                )
                ->groupBy('courses.Title', 'sections.name', 'student_attempts.section_id', 'sections.grade_visible')
                ->get();

            // Calculate scores per section
            $assessments = $progressData->map(function ($progress) use ($student) {
                $scores = DB::table('student_attempts')
                    ->where('student_id', $student->studentID)
                    ->where('section_id', $progress->section_id)
                    ->select('attempt_number', DB::raw('SUM(marks_obtained) as total_marks'))
                    ->groupBy('attempt_number')
                    ->get();

                $highestScore = $scores->max('total_marks') ?? 0;
                $totalMarks = Assessment::where('SectionID', $progress->section_id)->sum('grade');
                $percentage = $totalMarks > 0 ? round(($highestScore / $totalMarks) * 100, 1) : 0;

                return [
                    'course' => $progress->course_title,
                    'section' => $progress->section_name,
                    'section_id' => $progress->section_id,
                    'grade_visible' => $progress->grade_visible,
                    'attempts' => $progress->total_attempts,
                    'score' => $highestScore,
                    'total' => $totalMarks,
                    'percentage' => $percentage,
                    'last_attempt' => $progress->last_attempt
                ];
            });

            return [
                'student' => $student,
                'assessments' => $assessments
            ];
        });

        return view('parent.reports', compact('childrenData'));
    }

    /**
     * Student view: Own results/progress
     */
    public function studentResults()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        // Get all sections this student has attempted
        $progressData = DB::table('student_attempts')
            ->join('sections', 'student_attempts.section_id', '=', 'sections.id')
            ->join('courses', 'sections.CourseID', '=', 'courses.CourseID')
            ->where('student_attempts.student_id', $student->studentID)
            ->select(
                'courses.Title as course_title',
                'courses.CourseID as course_id',
                'sections.name as section_name',
                'sections.id as section_id',
                'student_attempts.section_id',
                'sections.grade_visible',
                'sections.review_enabled',
                'sections.attempt_limit',
                DB::raw('COUNT(DISTINCT attempt_number) as total_attempts'),
                DB::raw('MAX(student_attempts.created_at) as last_attempt')
            )
            ->groupBy('courses.Title', 'courses.CourseID', 'sections.name', 'sections.id', 'student_attempts.section_id', 'sections.grade_visible', 'sections.review_enabled', 'sections.attempt_limit')
            ->get();

        // Calculate scores per section
        $results = $progressData->map(function ($progress) use ($student) {
            $scores = DB::table('student_attempts')
                ->where('student_id', $student->studentID)
                ->where('section_id', $progress->section_id)
                ->select('attempt_number', DB::raw('SUM(marks_obtained) as total_marks'))
                ->groupBy('attempt_number')
                ->get();

            $highestScore = $scores->max('total_marks') ?? 0;
            $totalMarks = Assessment::where('SectionID', $progress->section_id)->sum('grade');
            $percentage = $totalMarks > 0 ? round(($highestScore / $totalMarks) * 100, 1) : 0;

            return [
                'course' => $progress->course_title,
                'course_id' => $progress->course_id,
                'section' => $progress->section_name,
                'section_id' => $progress->section_id,
                'grade_visible' => $progress->grade_visible,
                'review_enabled' => $progress->review_enabled,
                'attempts' => $progress->total_attempts,
                'can_retake' => ($progress->total_attempts < $progress->attempt_limit),
                'score' => $highestScore,
                'total' => $totalMarks,
                'percentage' => $percentage,
                'last_attempt' => $progress->last_attempt
            ];
        });

        // Group by course
        $resultsByCourse = $results->groupBy('course');

        // Calculate overall statistics
        $visibleGrades = $results->where('grade_visible', 1);
        $stats = [
            'total_assessments' => $results->count(),
            'total_attempts' => $results->sum('attempts'),
            'graded_assessments' => $visibleGrades->count(),
            'average_score' => $visibleGrades->count() > 0 ? round($visibleGrades->avg('percentage'), 1) : 0
        ];

        return view('student.results', compact('resultsByCourse', 'stats'));
    }
}
