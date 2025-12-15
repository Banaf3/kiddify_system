<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. User Stats
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalParents = ParentModel::count();
        $totalUsers = User::count();

        // 2. Course Stats
        $totalCourses = Course::count();

        // 3. Pending Approvals (Active vs Inactive)
        $pendingStudents = Student::where('account_status', 'inactive')->count();
        $pendingTeachers = Teacher::where('account_status', 'inactive')->count();

        // 4. Recent Activity (Newest Users)
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalParents',
            'totalUsers',
            'totalCourses',
            'pendingStudents',
            'pendingTeachers',
            'recentUsers'
        ));
    }
}
