<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Grading Overview
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Course and Section Selection -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Select Course and Assessment</h3>
                    
                    <form method="GET" action="{{ route('teacher.grading') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Course Selection -->
                            <div>
                                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Course
                                </label>
                                <select name="course_id" id="course_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        onchange="this.form.submit()">
                                    <option value="">-- Select a Course --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->CourseID }}" 
                                                {{ $selectedCourse && $selectedCourse->CourseID == $course->CourseID ? 'selected' : '' }}>
                                            {{ $course->Title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Section Selection -->
                            <div>
                                <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Assessment
                                </label>
                                <select name="section_id" id="section_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        onchange="this.form.submit()"
                                        {{ !$selectedCourse ? 'disabled' : '' }}>
                                    <option value="">-- Select an Assessment --</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" 
                                                {{ $selectedSection && $selectedSection->id == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Students Grades Table -->
            @if($selectedSection)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">
                            Students in {{ $selectedCourse->Title }} - {{ $selectedSection->name }}
                        </h3>

                        @if($studentsData->isEmpty())
                            <p class="text-gray-500 text-center py-4">No students enrolled in this course.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempts</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Highest Score</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($studentsData as $data)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $data['student']->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $data['student']->user->email }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($data['attempts'] > 0)
                                                        {{ $data['attempts'] }}
                                                    @else
                                                        <span class="text-gray-400 italic">No attempts</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($data['attempts'] > 0)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            @if($data['percentage'] >= 70) bg-green-100 text-green-800
                                                            @elseif($data['percentage'] >= 50) bg-yellow-100 text-yellow-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ $data['highest_score'] }}/{{ $data['total_marks'] }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 italic">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($data['attempts'] > 0)
                                                        <span class="font-semibold 
                                                            @if($data['percentage'] >= 70) text-green-600
                                                            @elseif($data['percentage'] >= 50) text-yellow-600
                                                            @else text-red-600
                                                            @endif">
                                                            {{ $data['percentage'] }}%
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400 italic">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($data['attempts'] > 0)
                                                        <a href="{{ route('teacher.grade-student', [$data['student']->studentID, $selectedSection->id]) }}" 
                                                           class="text-indigo-600 hover:text-indigo-900">
                                                            📝 Review Answers
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400 italic">No attempts yet</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($selectedCourse)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-500 text-center">
                        Please select an assessment to view student grades.
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-500 text-center">
                        Please select a course and assessment to view student grades.
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
