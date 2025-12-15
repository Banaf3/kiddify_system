<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;
use App\Models\StudentScore;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class ScorePolicy
{
    /**
     * Determine if user can view progress
     * Parents can only view their own children's progress
     */
    public function viewProgress(User $user, $studentId)
    {
        if ($user->role === 'student') {
            return $user->student->studentID == $studentId;
        }

        if ($user->role === 'parent') {
            // Check if this student is linked to this parent
            $isLinked = DB::table('family')
                ->where('UserID', $user->id)
                ->where('StudentID', $studentId)
                ->exists();

            return $isLinked;
        }

        return false;
    }

    /**
     * Determine if teacher can grade a student
     * Teachers can only grade students in their assigned courses
     */
    public function gradeStudent(User $user, Student $student)
    {
        if ($user->role !== 'teacher') {
            return false;
        }

        // Check if teacher is assigned to any course that the student is enrolled in
        $teacher = $user->teacher;

        $sharedCourses = DB::table('courses')
            ->join('course_student', 'courses.CourseID', '=', 'course_student.course_id')
            ->where('courses.teachersID', $teacher->teachersID)
            ->where('course_student.student_id', $student->studentID)
            ->exists();

        return $sharedCourses;
    }

    /**
     * Determine if student can review assessment
     * Students can only review if review is enabled by teacher
     */
    public function review(User $user, Section $section)
    {
        if ($user->role !== 'student') {
            return false;
        }

        // Check if review is enabled for this section
        if (!$section->review_enabled) {
            return false;
        }

        // Check if student is enrolled in this course
        $student = $user->student;
        $isEnrolled = DB::table('course_student')
            ->where('student_id', $student->studentID)
            ->where('course_id', $section->CourseID)
            ->exists();

        return $isEnrolled;
    }

    /**
     * Determine if student can view grades
     */
    public function viewGrade(User $user, Section $section)
    {
        if ($user->role !== 'student') {
            return false;
        }

        // Check if grades are visible for this section
        if (!$section->grade_visible) {
            return false;
        }

        // Check if student is enrolled
        $student = $user->student;
        $isEnrolled = DB::table('course_student')
            ->where('student_id', $student->studentID)
            ->where('course_id', $section->CourseID)
            ->exists();

        return $isEnrolled;
    }
}
