<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 My Assessment Results
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overall Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Total Assessments</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_assessments'] }}</p>
                    </div>
                </div>
                <div class="bg-purple-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Total Attempts</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['total_attempts'] }}</p>
                    </div>
                </div>
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Graded Assessments</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['graded_assessments'] }}</p>
                    </div>
                </div>
                <div class="bg-orange-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-600">Average Score</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $stats['average_score'] }}%</p>
                    </div>
                </div>
            </div>

            @if($resultsByCourse->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-500 text-center">
                        You haven't attempted any assessments yet.
                    </div>
                </div>
            @else
                @foreach($resultsByCourse as $courseName => $results)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">{{ $courseName }}</h3>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessment</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempts</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Best Score</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Attempt</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($results as $result)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $result['section'] }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $result['attempts'] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($result['grade_visible'])
                                                        <div>
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                @if($result['percentage'] >= 70) bg-green-100 text-green-800
                                                                @elseif($result['percentage'] >= 50) bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800
                                                                @endif">
                                                                {{ $result['score'] }}/{{ $result['total'] }}
                                                            </span>
                                                            <span class="ml-2 text-sm font-semibold 
                                                                @if($result['percentage'] >= 70) text-green-600
                                                                @elseif($result['percentage'] >= 50) text-yellow-600
                                                                @else text-red-600
                                                                @endif">
                                                                ({{ $result['percentage'] }}%)
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 italic text-sm">Pending grading</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($result['last_attempt'])->format('M d, Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex gap-2">
                                                        @if($result['review_enabled'])
                                                            <a href="{{ route('student.review-attempt', $result['section_id']) }}" 
                                                               class="text-purple-600 hover:text-purple-900">
                                                                📝 Review
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('student.courses.sections', $result['course_id']) }}" 
                                                           class="text-indigo-600 hover:text-indigo-900">
                                                            🔄 Retake
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Course Summary -->
                            <div class="mt-4 flex gap-4 text-sm">
                                <div class="bg-gray-50 px-4 py-2 rounded">
                                    <span class="text-gray-600">Total Assessments: </span>
                                    <span class="font-semibold">{{ $results->count() }}</span>
                                </div>
                                <div class="bg-gray-50 px-4 py-2 rounded">
                                    <span class="text-gray-600">Average: </span>
                                    @php
                                        $visibleGrades = $results->where('grade_visible', 1);
                                        $courseAvg = $visibleGrades->count() > 0 ? round($visibleGrades->avg('percentage'), 1) : 0;
                                    @endphp
                                    <span class="font-semibold 
                                        @if($courseAvg >= 70) text-green-600
                                        @elseif($courseAvg >= 50) text-yellow-600
                                        @else text-red-600
                                        @endif">
                                        {{ $courseAvg }}%
                                    </span>
                                    @if($visibleGrades->count() === 0)
                                        <span class="text-xs text-gray-500">(No graded assessments)</span>
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
