<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#5A9CB5] leading-tight">
            📊 Children Progress Reports
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($childrenData->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-[24px] border border-gray-100">
                    <div class="p-6 text-gray-500 text-center">
                        No children found in your account.
                    </div>
                </div>
            @else
                @foreach($childrenData as $childData)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-[24px] mb-6 border border-gray-100">
                        <div class="p-6 bg-white border-b border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-[#5A9CB5]">{{ $childData['student']->user->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $childData['student']->user->email }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Total Assessments</p>
                                    <p class="text-2xl font-bold text-[#5A9CB5]">{{ $childData['assessments']->count() }}</p>
                                </div>
                            </div>

                            @if($childData['assessments']->isEmpty())
                                <p class="text-gray-500 text-center py-4">No assessments attempted yet.</p>
                            @else
                                <div class="overflow-x-auto rounded-xl border border-gray-100">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-[#FACE68]">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Course</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Assessment</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Attempts</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Score</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Last Attempt</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($childData['assessments'] as $assessment)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                                        {{ $assessment['course'] }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                        {{ $assessment['section'] }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-md text-xs font-bold">
                                                            {{ $assessment['attempts'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($assessment['grade_visible'])
                                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                                @if($assessment['percentage'] >= 70) bg-[#5A9CB5]/10 text-[#5A9CB5]
                                                                @elseif($assessment['percentage'] >= 50) bg-[#FACE68]/10 text-[#FAAC68]
                                                                @else bg-[#FA6868]/10 text-[#FA6868]
                                                                @endif">
                                                                {{ $assessment['score'] }}/{{ $assessment['total'] }} ({{ $assessment['percentage'] }}%)
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400 italic text-sm">Not yet graded</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($assessment['last_attempt'])->format('M d, Y H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Child Summary Statistics -->
                                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-[#5A9CB5]/10 p-4 rounded-[20px] border border-[#5A9CB5]/20">
                                        <p class="text-sm text-gray-600 font-bold">Total Assessments</p>
                                        <p class="text-3xl font-bold text-[#5A9CB5]">{{ $childData['assessments']->count() }}</p>
                                    </div>
                                    <div class="bg-[#FACE68]/10 p-4 rounded-[20px] border border-[#FACE68]/20">
                                        <p class="text-sm text-gray-600 font-bold">Total Attempts</p>
                                        <p class="text-3xl font-bold text-[#FAAC68]">{{ $childData['assessments']->sum('attempts') }}</p>
                                    </div>
                                    <div class="bg-[#9333EA]/10 p-4 rounded-[20px] border border-[#9333EA]/20">
                                        <p class="text-sm text-gray-600 font-bold">Average Score</p>
                                        <p class="text-3xl font-bold text-[#9333EA]">
                                            @php
                                                $visibleScores = $childData['assessments']->where('grade_visible', 1);
                                                $avgScore = $visibleScores->count() > 0 ? round($visibleScores->avg('percentage'), 1) : 0;
                                            @endphp
                                            {{ $avgScore }}%
                                            @if($visibleScores->count() === 0)
                                                <span class="text-xs text-gray-500 block">No graded assessments</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
