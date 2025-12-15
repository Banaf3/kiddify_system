<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    /**
     * STUDENT DASHBOARD — LIST COURSES
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if the logged-in user is a student
        if (!$user || !$user->isStudent()) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        /** @var Student $student */
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        // Get all courses the student is enrolled in with teacher info
        $courses = $student->courses()->with('teacher')->get();

        return view('Module2.student.index', compact('student', 'courses'));
    }

    /**
     * VIEW SINGLE COURSE DETAILS
     *
     * @param int $courseID
     * @return \Illuminate\View\View
     */
    public function viewCourse($courseID)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isStudent()) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        /** @var Student $student */
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        // Get course with teacher and enrolled students
        $course = Course::with('teacher', 'students')->findOrFail($courseID);

        // Ensure student is enrolled in the course
        if (!$course->students->contains($student->studentID)) {
            abort(403, 'You are not enrolled in this course.');
        }

        return view('Module2.student.show', compact('course'));
    }

    /**
     * VIEW STUDENT TIMETABLE
     *
     * @return \Illuminate\View\View
     */
    public function timetable()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isStudent()) {
            abort(403, 'Unauthorized access. Please log in as a student.');
        }

        /** @var Student $student */
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        // Get all enrolled courses with teacher info
        $courses = $student->courses()->with('teacher')->get();

        // Prepare days of the week
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('Module2.student.timetable', compact('courses', 'daysOfWeek'));
    }
    public function downloadTimetable()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return back()->withErrors("Student profile not found.");
        }

        $courses = $student->courses()->with('teacher.user')->get();
        $startHour = 7;
        $endHour = 19;
        $dayColumns = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $timeSlots = [];
        for ($h = $startHour; $h < $endHour; $h++) {
            $label = sprintf('%02d:00 - %02d:00', $h, $h + 1);
            $slotClasses = [];

            foreach ($dayColumns as $day) {
                $class = $courses->first(function ($c) use ($day, $h) {
                    $days = json_decode($c->days, true);
                    $classHour = intval(\Carbon\Carbon::parse($c->Start_time)->format('H'));
                    return in_array($day, $days) && $classHour == $h;
                });

                if ($class) {
                    $slotClasses[$day] = $class;
                }
            }

            $timeSlots[] = [
                'label' => $label,
                'classes' => $slotClasses
            ];
        }

        return Pdf::loadView('Module2.student.timetablePDF', compact('dayColumns', 'timeSlots'))
            ->download('My_Timetable.pdf');
    }
    /**
     * STUDENT DASHBOARD - OVERVIEW
     */
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user || !$user->isStudent()) {
            abort(403, 'Unauthorized access.');
        }

        $student = $user->student;
        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        // 1. Enrolled Courses Count
        $enrolledCoursesCount = $student->courses()->count();

        // 2. Pending Assessments (Assessments in enrolled courses that haven't been attempted)
        // This is a simplified check. For rigorous pending logic, we'd check attempt counts against limits.
        $pendingAssessmentsCount = 0;
        $courses = $student->courses()->with(['sections.assessments'])->get();

        foreach ($courses as $course) {
            foreach ($course->sections as $section) {
                if ($section->canAttempt()) {
                    $pendingAssessmentsCount++;
                }
            }
        }

        // 3. Average Grade Calculation
        // Get all graded attempts
        $attempts = \Illuminate\Support\Facades\DB::table('student_attempts')
            ->join('sections', 'student_attempts.section_id', '=', 'sections.id')
            ->where('student_attempts.student_id', $student->studentID)
            ->where('sections.grade_visible', true)
            ->select('student_attempts.marks_obtained', 'student_attempts.section_id')
            ->get();

        // Naive average: sum of marks / count (Improvement: normalize by total marks per assessment)
        // For better accuracy, we should calculate percentage per assessment.

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

        $averageGrade = $gradedCount > 0 ? round($totalPercentage / $gradedCount, 1) : 0;

        return view('student.dashboard', compact('student', 'enrolledCoursesCount', 'pendingAssessmentsCount', 'averageGrade'));
    }
}
