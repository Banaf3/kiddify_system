<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👩‍🏫 {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-800">Welcome, {{ Auth::user()->name }}! 🍎</h3>
                    <p class="text-gray-600 mt-2">Manage your classes, students, and assessments efficiently.</p>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Active Courses --}}
                <div class="bg-purple-50 p-6 rounded-lg shadow border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <span class="text-2xl">📚</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium uppercase">Active Courses</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $activeCoursesCount }}</p>
                        </div>
                    </div>
                </div>

                {{-- Total Students --}}
                <div class="bg-blue-50 p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <span class="text-2xl">🎓</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium uppercase">Total Students</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalStudents }}</p>
                        </div>
                    </div>
                </div>

                {{-- Pending Grading --}}
                <div class="bg-orange-50 p-6 rounded-lg shadow border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                            <span class="text-2xl">✏️</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium uppercase">Pending Grading</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $pendingGradingCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('teacher.courses') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">📋 Manage Courses</h4>
                    <p class="text-gray-500 text-sm">View details, students, and sections.</p>
                </a>

                <a href="{{ route('teacher.grading') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">💯 Grading Center</h4>
                    <p class="text-gray-500 text-sm">Grade student assessments and submissions.</p>
                </a>

                <a href="{{ route('teacher.schedule') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">🗓️ My Schedule</h4>
                    <p class="text-gray-500 text-sm">View your teaching timetable.</p>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>