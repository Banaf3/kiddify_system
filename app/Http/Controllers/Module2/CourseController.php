<?php

namespace App\Http\Controllers\Module2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Student;

class CourseController extends Controller
{
    // -----------------------------
    // LIST ALL COURSES
    // -----------------------------
    public function index()
    {
        $courses = Course::with('teacher')->get();
        return view('Module2.admin.index', compact('courses'));
    }

    // -----------------------------
    // SHOW CREATE FORM
    // -----------------------------
    public function create()
    {
        $teachers = Teacher::all();
        $students = Student::all();
        return view('Module2.admin.create', compact('teachers', 'students'));
    }

    // -----------------------------
    // STORE NEW COURSE
    // -----------------------------
    public function store(Request $request)
    {
        $request->validate([
            'Title' => 'required|string|max:255',
            'description' => 'required|string',
            'teachersID' => 'required|exists:teachers,teachersID',
            'Start_time' => 'required',
            'end_time' => 'required|after:Start_time',
            'days' => 'required|array|min:1',
            'student_ids' => ['required','array','max:15'], // MAX 3 students for testing
        ], [
            'student_ids.max' => 'You cannot assign more than 15 students to a course.' // Custom message
        ]);

        // Check for schedule conflicts
        $conflict = $this->checkScheduleConflict(
            $request->teachersID,
            $request->student_ids,
            $request->days,
            $request->Start_time,
            $request->end_time
        );

        if ($conflict !== true) {
            return redirect()->back()->withInput()->withErrors(['schedule' => $conflict]);
        }

        $course = Course::create([
            'Title' => $request->Title,
            'teachersID' => $request->teachersID,
            'description' => $request->description,
            'Start_time' => $request->Start_time,
            'end_time' => $request->end_time,
            'days' => json_encode($request->days),
        ]);

        $course->students()->sync($request->student_ids);

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course created successfully.');
    }

    // -----------------------------
    // SHOW EDIT FORM
    // -----------------------------
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $teachers = Teacher::all();
        $students = Student::all();
        $selectedStudents = $course->students->pluck('studentID')->toArray();
        $selectedDays = json_decode($course->days, true);

        return view('Module2.admin.edit', compact(
            'course', 'teachers', 'students', 'selectedStudents', 'selectedDays'
        ));
    }

    // -----------------------------
    // UPDATE COURSE
    // -----------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            'Title' => 'required|string|max:255',
            'teachersID' => 'required|exists:teachers,teachersID',
            'Start_time' => 'required',
            'end_time' => 'required|after:Start_time',
            'days' => 'required|array',
            'student_ids' => ['required','array','max:15'], // MAX 3 students for testing
        ], [
            'student_ids.max' => 'You cannot assign more than 15 students to a course.' // Custom message
        ]);

        // Check for schedule conflicts
        $conflict = $this->checkScheduleConflict(
            $request->teachersID,
            $request->student_ids,
            $request->days,
            $request->Start_time,
            $request->end_time,
            $id
        );

        if ($conflict !== true) {
            return redirect()->back()->withInput()->withErrors(['schedule' => $conflict]);
        }

        $course = Course::findOrFail($id);

        $course->update([
            'Title' => $request->Title,
            'teachersID' => $request->teachersID,
            'description' => $request->description,
            'Start_time' => $request->Start_time,
            'end_time' => $request->end_time,
            'days' => json_encode($request->days),
        ]);

        $course->students()->sync($request->student_ids);

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course updated successfully.');
    }

    // -----------------------------
    // DELETE COURSE
    // -----------------------------
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->students()->detach();
        $course->delete();

        return redirect()->route('admin.courses.index')
                         ->with('success', 'Course deleted successfully.');
    }

    // -----------------------------
    // VIEW STUDENTS ENROLLED IN COURSE
    // -----------------------------
    public function viewStudents($id)
    {
        $course = Course::findOrFail($id);
        $students = $course->students;

        return view('Module2.admin.students', compact('course', 'students'));
    }

    // -----------------------------
    // CHECK FOR SCHEDULE CONFLICT
    // -----------------------------
    private function checkScheduleConflict($teacherID, $studentIDs, $days, $startTime, $endTime, $excludeCourseID = null)
    {
        // Teacher conflict
        $teacherCourses = Course::where('teachersID', $teacherID)
            ->when($excludeCourseID, fn($q) => $q->where('CourseID', '!=', $excludeCourseID))
            ->get();

        foreach ($teacherCourses as $course) {
            $courseDays = json_decode($course->days, true);
            foreach ($days as $day) {
                if (in_array($day, $courseDays) && $this->timeOverlap($startTime, $endTime, $course->Start_time, $course->end_time)) {
                    return "Teacher has another course on $day from {$course->Start_time} to {$course->end_time}";
                }
            }
        }

        // Student conflict
        foreach ($studentIDs as $studentID) {
            $studentCourses = Student::find($studentID)->courses()
                ->when($excludeCourseID, fn($q) => $q->where('CourseID', '!=', $excludeCourseID))
                ->get();

            foreach ($studentCourses as $course) {
                $courseDays = json_decode($course->days, true);
                foreach ($days as $day) {
                    if (in_array($day, $courseDays) && $this->timeOverlap($startTime, $endTime, $course->Start_time, $course->end_time)) {
                        return "Student ID $studentID has a conflict on $day from {$course->Start_time} to {$course->end_time}";
                    }
                }
            }
        }

        return true; // no conflicts
    }

    // -----------------------------
    // CHECK IF TIME OVERLAPS
    // -----------------------------
    private function timeOverlap($start1, $end1, $start2, $end2)
    {
        return !($end1 <= $start2 || $start1 >= $end2);
    }
}
