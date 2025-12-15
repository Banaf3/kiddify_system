<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏠 {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-800">Welcome back, {{ Auth::user()->name }}! 👋</h3>
                    <p class="text-gray-600 mt-2">Here's a quick overview of your learning progress.</p>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Enrolled Courses --}}
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Enrolled Courses</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $enrolledCoursesCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Assessments --}}
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pending Assessments</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $pendingAssessmentsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Average Grade --}}
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Average Grade</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $averageGrade }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('student.courses.index') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">📚 My Courses</h4>
                    <p class="text-gray-500 text-sm">Access your course materials and lessons.</p>
                </a>

                <a href="{{ route('student.assessments') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">📝 My Assessments</h4>
                    <p class="text-gray-500 text-sm">Take quizzes and view your results.</p>
                </a>

                <a href="{{ route('student.results') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">🏆 My Progress</h4>
                    <p class="text-gray-500 text-sm">Check your grades and performance.</p>
                </a>

                <a href="{{ route('student.timetable') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">📅 TimeTable</h4>
                    <p class="text-gray-500 text-sm">Check your weekly schedule.</p>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>