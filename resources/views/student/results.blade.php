<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-[#5A9CB5] leading-tight">
            📊 {{ __('My Assessment Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overall Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-[#5A9CB5]/10 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-bold text-gray-600">Total Assessments</p>
                        <p class="text-3xl font-extrabold text-[#5A9CB5]">{{ $stats['total_assessments'] }}</p>
                    </div>
                </div>
                <div class="bg-[#FA6868]/10 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-bold text-gray-600">Total Attempts</p>
                        <p class="text-3xl font-extrabold text-[#FA6868]">{{ $stats['total_attempts'] }}</p>
                    </div>
                </div>
                <div class="bg-[#FACE68]/10 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-bold text-gray-600">Graded Assessments</p>
                        <p class="text-3xl font-extrabold text-[#FAAC68]">{{ $stats['graded_assessments'] }}</p>
                    </div>
                </div>
                <div class="bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-bold text-gray-600">Average Score</p>
                        <p class="text-3xl font-extrabold text-gray-700">{{ $stats['average_score'] }}%</p>
                    </div>
                </div>
            </div>

            @if($resultsByCourse->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-gray-400 text-center font-medium text-lg">
                        You haven't attempted any assessments yet.
                    </div>
                </div>
            @else
                @foreach($resultsByCourse as $courseName => $results)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-100">
                        <div class="p-6 bg-white border-b border-gray-100">
                            <div class="flex items-center gap-2 mb-6">
                                <div class="h-6 w-1 rounded-full bg-[#5A9CB5]"></div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $courseName }}</h3>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-gray-100">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-[#5A9CB5]/5">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-[#5A9CB5] uppercase tracking-wider">
                                                Assessment</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-[#5A9CB5] uppercase tracking-wider">
                                                Attempts</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-[#5A9CB5] uppercase tracking-wider">
                                                Best Score</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-[#5A9CB5] uppercase tracking-wider">
                                                Last Attempt</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-[#5A9CB5] uppercase tracking-wider">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach($results as $result)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-700">
                                                        {{ $result['section'] }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                                    {{ $result['attempts'] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($result['grade_visible'])
                                                        <div>
                                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                                                @if($result['percentage'] >= 70) bg-green-100 text-green-700
                                                                                @elseif($result['percentage'] >= 50) bg-[#FACE68]/20 text-[#FAAC68]
                                                                                @else bg-[#FA6868]/10 text-[#FA6868]
                                                                                @endif">
                                                                {{ $result['score'] }}/{{ $result['total'] }}
                                                            </span>
                                                            <span class="ml-2 text-sm font-bold 
                                                                                @if($result['percentage'] >= 70) text-green-600
                                                                                @elseif($result['percentage'] >= 50) text-[#FAAC68]
                                                                                @else text-[#FA6868]
                                                                                @endif">
                                                                ({{ $result['percentage'] }}%)
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 italic text-sm font-medium">Pending grading</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($result['last_attempt'])->format('M d, Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                                    <div class="flex gap-4">
                                                        @if($result['review_enabled'])
                                                            <a href="{{ route('student.review-attempt', $result['section_id']) }}" 
                                                               class="text-[#5A9CB5] hover:text-[#4A8CA5] transition-colors no-underline flex items-center gap-1">
                                                                📝 Review
                                                            </a>
                                                        @endif
                                                        @if($result['can_retake'])
                                                            <a href="{{ route('student.courses.sections', $result['course_id']) }}" 
                                                               class="text-[#FA6868] hover:text-[#E85858] transition-colors no-underline flex items-center gap-1">
                                                                🔄 Retake
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Course Summary -->
                            <div class="mt-6 flex flex-wrap gap-4 text-sm">
                                <div class="bg-gray-50 px-4 py-2 rounded-lg border border-gray-100">
                                    <span class="text-gray-500 font-medium">Total Assessments: </span>
                                    <span class="font-bold text-gray-800">{{ $results->count() }}</span>
                                </div>
                                <div class="bg-gray-50 px-4 py-2 rounded-lg border border-gray-100">
                                    <span class="text-gray-500 font-medium">Average: </span>
                                    @php
                                        $visibleGrades = $results->where('grade_visible', 1);
                                        $courseAvg = $visibleGrades->count() > 0 ? round($visibleGrades->avg('percentage'), 1) : 0;
                                    @endphp
                                    <span class="font-bold 
                                                @if($courseAvg >= 70) text-green-600
                                                @elseif($courseAvg >= 50) text-[#FAAC68]
                                                @else text-[#FA6868]
                                                @endif">
                                        {{ $courseAvg }}%
                                    </span>
                                    @if($visibleGrades->count() === 0)
                                        <span class="text-xs text-gray-400 ml-1">(No graded assessments)</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>