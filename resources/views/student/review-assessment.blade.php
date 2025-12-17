<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-[#5A9CB5] leading-tight">
            📊 {{ __('Review Assessment') }} - <span class="text-gray-600">{{ $section->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-700">Attempt #{{ $attemptNumber }}</h3>
                        @if($gradeVisible)
                            <div class="text-right">
                                <p class="text-3xl font-extrabold text-[#5A9CB5]">
                                    {{ $marksObtained }}<span class="text-lg text-gray-400">/{{ $totalMarks }}</span>
                                </p>
                                <p class="text-sm text-gray-500 font-medium">
                                    {{ $totalMarks > 0 ? round(($marksObtained / $totalMarks) * 100, 1) : 0 }}% Score
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
                        <div class="mb-8 flex flex-wrap gap-2 items-center">
                            <span class="text-sm font-bold text-gray-500 uppercase tracking-wide mr-2">Attempts:</span>
                            @foreach($allAttempts as $num)
                                <a href="{{ route('student.review-attempt', [$section->id, $num]) }}" 
                                   class="px-4 py-1.5 rounded-full text-sm font-bold transition-all duration-200 no-underline @if($num == $attemptNumber) bg-[#5A9CB5] text-white shadow-md @else bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 @endif">
                                    #{{ $num }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-6">
                        @foreach($assessments as $assessment)
                            <div class="p-6 rounded-2xl border transition-colors duration-200
                                @if($gradeVisible)
                                    @if($assessment->was_correct) bg-green-50/50 border-green-200 @else bg-[#FA6868]/5 border-[#FA6868]/20 @endif
                                @else
                                    bg-gray-50 border-gray-100
                                @endif">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        @if($assessment->image)
                                            <div class="mb-4">
                                                <img src="{{ asset('storage/' . $assessment->image) }}" 
                                                     alt="Question" 
                                                     class="max-w-[300px] h-auto rounded-xl border border-gray-200 shadow-sm">
                                            </div>
                                        @endif
                                        
                                        <p class="font-bold text-lg text-gray-800 mb-4">{{ $assessment->question }}</p>
                                        
                                        <div class="space-y-3">
                                            {{-- Option A --}}
                                            <div class="flex items-center p-3 rounded-xl border transition-all
                                                @if($gradeVisible)
                                                    @if($assessment->student_answer === 'A')
                                                        @if($assessment->correct_answer === 'A') bg-green-100 border-green-200 @else bg-[#FA6868]/20 border-[#FA6868]/30 @endif
                                                    @elseif($assessment->correct_answer === 'A') bg-green-100 border-green-200 @else border-transparent bg-white/50 @endif
                                                @else
                                                    @if($assessment->student_answer === 'A') bg-[#5A9CB5]/10 border-[#5A9CB5]/30 @else border-transparent bg-white/50 @endif
                                                @endif">
                                                <span class="w-8 font-bold text-gray-500">A.</span>
                                                <span class="flex-1 font-medium text-gray-700">{{ $assessment->optionA }}</span>
                                                @if($assessment->student_answer === 'A')
                                                    <span class="ml-2 text-xs font-bold uppercase tracking-wider text-gray-500">Your Answer</span>
                                                @endif
                                                @if($gradeVisible && $assessment->correct_answer === 'A')
                                                    <span class="ml-2 text-green-600 font-bold text-lg">✓</span>
                                                @endif
                                                @if($gradeVisible && $assessment->student_answer === 'A' && $assessment->correct_answer !== 'A')
                                                    <span class="ml-2 text-[#FA6868] font-bold text-lg">✗</span>
                                                @endif
                                            </div>

                                            {{-- Option B --}}
                                            <div class="flex items-center p-3 rounded-xl border transition-all
                                                @if($gradeVisible)
                                                    @if($assessment->student_answer === 'B')
                                                        @if($assessment->correct_answer === 'B') bg-green-100 border-green-200 @else bg-[#FA6868]/20 border-[#FA6868]/30 @endif
                                                    @elseif($assessment->correct_answer === 'B') bg-green-100 border-green-200 @else border-transparent bg-white/50 @endif
                                                @else
                                                    @if($assessment->student_answer === 'B') bg-[#5A9CB5]/10 border-[#5A9CB5]/30 @else border-transparent bg-white/50 @endif
                                                @endif">
                                                <span class="w-8 font-bold text-gray-500">B.</span>
                                                <span class="flex-1 font-medium text-gray-700">{{ $assessment->optionB }}</span>
                                                @if($assessment->student_answer === 'B')
                                                    <span class="ml-2 text-xs font-bold uppercase tracking-wider text-gray-500">Your Answer</span>
                                                @endif
                                                @if($gradeVisible && $assessment->correct_answer === 'B')
                                                    <span class="ml-2 text-green-600 font-bold text-lg">✓</span>
                                                @endif
                                                @if($gradeVisible && $assessment->student_answer === 'B' && $assessment->correct_answer !== 'B')
                                                    <span class="ml-2 text-[#FA6868] font-bold text-lg">✗</span>
                                                @endif
                                            </div>

                                            {{-- Option C --}}
                                            <div class="flex items-center p-3 rounded-xl border transition-all
                                                @if($gradeVisible)
                                                    @if($assessment->student_answer === 'C')
                                                        @if($assessment->correct_answer === 'C') bg-green-100 border-green-200 @else bg-[#FA6868]/20 border-[#FA6868]/30 @endif
                                                    @elseif($assessment->correct_answer === 'C') bg-green-100 border-green-200 @else border-transparent bg-white/50 @endif
                                                @else
                                                    @if($assessment->student_answer === 'C') bg-[#5A9CB5]/10 border-[#5A9CB5]/30 @else border-transparent bg-white/50 @endif
                                                @endif">
                                                <span class="w-8 font-bold text-gray-500">C.</span>
                                                <span class="flex-1 font-medium text-gray-700">{{ $assessment->optionC }}</span>
                                                @if($assessment->student_answer === 'C')
                                                    <span class="ml-2 text-xs font-bold uppercase tracking-wider text-gray-500">Your Answer</span>
                                                @endif
                                                @if($gradeVisible && $assessment->correct_answer === 'C')
                                                    <span class="ml-2 text-green-600 font-bold text-lg">✓</span>
                                                @endif
                                                @if($gradeVisible && $assessment->student_answer === 'C' && $assessment->correct_answer !== 'C')
                                                    <span class="ml-2 text-[#FA6868] font-bold text-lg">✗</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($gradeVisible)
                                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                                <p class="text-sm font-bold text-gray-500">
                                                    Marks: <span class="text-gray-800">{{ $assessment->marks_earned }}</span> / {{ $assessment->grade }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    @if($gradeVisible)
                                        <div class="ml-4 shrink-0">
                                            @if($assessment->was_correct)
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700 border border-green-200 shadow-sm">
                                                    ✓ Correct
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-[#FA6868]/10 text-[#FA6868] border border-[#FA6868]/20 shadow-sm">
                                                    ✗ Incorrect
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        <a href="{{ $backUrl }}" 
                           class="inline-flex items-center px-6 py-3 bg-[#5A9CB5] hover:bg-[#4A8CA5] border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-widest shadow-lg hover:shadow-xl transition-all duration-200 no-underline">
                            ← {{ $backText }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
