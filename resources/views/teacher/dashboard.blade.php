<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-[#5A9CB5] leading-tight">
            👩‍🏫 {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome --}}
            <div
                class="bg-gradient-to-r from-[#FACE68] to-[#F0A058] rounded-[32px] px-8 pb-8 pt-12 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-3xl font-extrabold mb-2">Welcome, {{ Auth::user()->name }}! 🍎</h3>
                    <p class="text-yellow-50 text-lg">Inspiring the next generation, one lesson at a time.</p>
                </div>
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-20 rounded-full"></div>
            </div>

            {{-- Quick Actions --}}
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-6 pl-2">Classroom Management</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('teacher.courses') }}"
                        class="group bg-white rounded-[32px] p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-b-8 border-[#5A9CB5] no-underline">
                        <div class="text-center group-hover:scale-110 transition-transform duration-300">
                            <div class="text-5xl mb-4">📋</div>
                            <h4 class="text-xl font-bold text-gray-800 group-hover:text-[#5A9CB5] mb-2">Manage Courses
                            </h4>
                            <p class="text-gray-500 group-hover:text-gray-700 text-sm font-medium">Curriculum & Students
                            </p>
                        </div>
                    </a>

                    <a href="{{ route('teacher.grading') }}"
                        class="group bg-white rounded-[32px] p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-b-8 border-[#FACE68] no-underline">
                        <div class="text-center group-hover:scale-110 transition-transform duration-300">
                            <div class="text-5xl mb-4">💯</div>
                            <h4 class="text-xl font-bold text-gray-800 group-hover:text-[#F0A058] mb-2">Grading Center
                            </h4>
                            <p class="text-gray-500 group-hover:text-gray-700 text-sm font-medium">Review Submissions
                            </p>
                        </div>
                    </a>

                    <a href="{{ route('teacher.schedule') }}"
                        class="group bg-white rounded-[32px] p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-b-8 border-[#FAAC68] no-underline">
                        <div class="text-center group-hover:scale-110 transition-transform duration-300">
                            <div class="text-5xl mb-4">🗓️</div>
                            <h4 class="text-xl font-bold text-gray-800 group-hover:text-[#FAAC68] mb-2">My Schedule</h4>
                            <p class="text-gray-500 group-hover:text-gray-700 text-sm font-medium">Timetable & Events
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Active Courses --}}
                <div
                    class="bg-white rounded-[32px] p-6 shadow-[0_10px_30px_rgba(90,156,181,0.1)] border-2 border-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 rounded-2xl bg-[#5A9CB5]/10 text-[#5A9CB5]">
                            <span class="text-3xl">📚</span>
                        </div>
                        <span class="text-[#5A9CB5] font-bold text-4xl">{{ $activeCoursesCount }}</span>
                    </div>
                    <h4 class="text-gray-800 font-bold text-lg">Active Courses</h4>
                    <p class="text-gray-500 text-sm">Classes currently in session</p>
                </div>

                {{-- Total Students --}}
                <div
                    class="bg-white rounded-[32px] p-6 shadow-[0_10px_30px_rgba(90,156,181,0.1)] border-2 border-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 rounded-2xl bg-[#FAAC68]/10 text-[#FAAC68]">
                            <span class="text-3xl">🎓</span>
                        </div>
                        <span class="text-[#FAAC68] font-bold text-4xl">{{ $totalStudents }}</span>
                    </div>
                    <h4 class="text-gray-800 font-bold text-lg">Total Students</h4>
                    <p class="text-gray-500 text-sm">Young minds growing</p>
                </div>

                {{-- Pending Grading --}}
                <div
                    class="bg-white rounded-[32px] p-6 shadow-[0_10px_30px_rgba(90,156,181,0.1)] border-2 border-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 rounded-2xl bg-[#FA6868]/10 text-[#FA6868]">
                            <span class="text-3xl">✏️</span>
                        </div>
                        <span class="text-[#FA6868] font-bold text-4xl">{{ $pendingGradingCount }}</span>
                    </div>
                    <h4 class="text-gray-800 font-bold text-lg">Pending Grading</h4>
                    <p class="text-gray-500 text-sm">Assessments to review</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>