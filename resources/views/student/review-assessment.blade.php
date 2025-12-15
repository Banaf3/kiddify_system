<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Review Assessment - {{ $section->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Attempt #{{ $attemptNumber }}</h3>
                        @if($gradeVisible)
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ $marksObtained }}/{{ $totalMarks }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $totalMarks > 0 ? round(($marksObtained / $totalMarks) * 100, 1) : 0 }}%
                                </p>
                            </div>
                        @else
                            <div class="text-right">
                                <p class="text-sm text-gray-600 italic">
                                    Scores will be visible once graded
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($allAttempts->count() > 1)
                        <div class="mb-6 flex gap-2">
                            <span class="text-sm font-semibold text-gray-700">Other Attempts:</span>
                            @foreach($allAttempts as $num)
                                <a href="{{ route('student.review-attempt', [$section->id, $num]) }}" 
                                   class="px-3 py-1 rounded @if($num == $attemptNumber) bg-blue-600 text-white @else bg-gray-200 text-gray-700 hover:bg-gray-300 @endif">
                                    #{{ $num }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-4">
                        @foreach($assessments as $assessment)
                            <div class="p-4 rounded border @if($gradeVisible) @if($assessment->was_correct) bg-green-50 border-green-200 @else bg-red-50 border-red-200 @endif @else bg-gray-50 border-gray-200 @endif">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        @if($assessment->image)
                                            <img src="{{ asset('storage/' . $assessment->image) }}" 
                                                 alt="Question" 
                                                 class="w-48 h-auto rounded mb-3 border">
                                        @endif
                                        
                                        <p class="font-semibold text-gray-800 mb-3">{{ $assessment->question }}</p>
                                        
                                        <div class="space-y-2">
                                            <div class="flex items-center p-2 rounded 
                                                @if($gradeVisible)
                                                    @if($assessment->student_answer === 'A')
                                                        @if($assessment->correct_answer === 'A') bg-green-100 @else bg-red-100 @endif
                                                    @elseif($assessment->correct_answer === 'A') bg-green-100 @endif
                                                @else
                                                    @if($assessment->student_answer === 'A') bg-blue-100 @endif
                                                @endif">
                                                <span class="w-8 font-semibold">A.</span>
                                                <span class="flex-1">{{ $assessment->optionA }}</span>
                                                @if($assessment->student_answer === 'A')
                                                    <span class="ml-2 text-sm font-semibold">← Your Answer</span>
                                                @endif
                                                @if($gradeVisible && $assessment->correct_answer === 'A')
                                                    <span class="ml-2 text-green-600 font-bold">✓</span>
                                                @endif
                                            </div>

                                            <div class="flex items-center p-2 rounded 
                                                @if($gradeVisible)
                                                    @if($assessment->student_answer === 'B')
                                                        @if($assessment->correct_answer === 'B') bg-green-100 @else bg-red-100 @endif
                                                    @elseif($assessment->correct_answer === 'B') bg-green-100 @endif
                                                @else
                                                    @if($assessment->student_answer === 'B') bg-blue-100 @endif
                                                @endif">
                                                <span class="w-8 font-semibold">B.</span>
                                                <span class="flex-1">{{ $assessment->optionB }}</span>
                                                @if($assessment->student_answer === 'B')
                                                    <span class="ml-2 text-sm font-semibold">← Your Answer</span>
                                                @endif
                                                @if($gradeVisible && $assessment->correct_answer === 'B')
                                                    <span class="ml-2 text-green-600 font-bold">✓</span>
                                                @endif
                                            </div>

                                            <div class="flex items-center p-2 rounded 
                                                @if($gradeVisible)
                                                    @if($assessment->student_answer === 'C')
                                                        @if($assessment->correct_answer === 'C') bg-green-100 @else bg-red-100 @endif
                                                    @elseif($assessment->correct_answer === 'C') bg-green-100 @endif
                                                @else
                                                    @if($assessment->student_answer === 'C') bg-blue-100 @endif
                                                @endif">
                                                <span class="w-8 font-semibold">C.</span>
                                                <span class="flex-1">{{ $assessment->optionC }}</span>
                                                @if($assessment->student_answer === 'C')
                                                    <span class="ml-2 text-sm font-semibold">← Your Answer</span>
                                                @endif
                                                @if($gradeVisible && $assessment->correct_answer === 'C')
                                                    <span class="ml-2 text-green-600 font-bold">✓</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($gradeVisible)
                                            <div class="mt-3 pt-3 border-t">
                                                <p class="text-sm font-semibold">
                                                    Marks: {{ $assessment->marks_earned }}/{{ $assessment->grade }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    @if($gradeVisible)
                                        <div class="ml-4">
                                            @if($assessment->was_correct)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    ✓ Correct
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                    ✗ Incorrect
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <a href="{{ $backUrl }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            ← {{ $backText }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
