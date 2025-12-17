<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-[#5A9CB5] leading-tight">
            📄 {{ __('My Assessments') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Course Selection Tabs --}}
            <div class="mb-4 flex space-x-3 overflow-x-auto p-2 no-scrollbar">
                @foreach($courses as $course)
                    @php
                        $assessmentCount = $course->sections->sum(function ($section) {
                            return $section->assessments->count();
                        });
                    @endphp
                    <button onclick="filterCourse('{{ $course->CourseID }}')"
                        class="course-tab px-6 py-3 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-300 shadow-sm
                               {{ $loop->first ? 'bg-[#5A9CB5] text-white shadow-md transform scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-100' }}"
                        data-course-id="{{ $course->CourseID }}">
                        {{ $course->Title }} <span class="ml-2 bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ $assessmentCount }}</span>
                    </button>
                @endforeach
            </div>

            @forelse($courses as $course)
                <div id="course-container-{{ $course->CourseID }}"
                    class="course-container mb-8 {{ $loop->first ? '' : 'hidden' }}">
                    
                    <div class="flex items-center gap-3 mb-6 px-2">
                        <div class="h-8 w-1 rounded-full bg-[#5A9CB5]"></div>
                        <h3 class="font-bold text-2xl text-gray-800">
                            {{ $course->Title }}
                        </h3>
                    </div>

                    <div class="space-y-6">
                        @forelse($course->sections as $section)
                            <div class="bg-white shadow-[0_10px_30px_rgba(0,0,0,0.05)] rounded-[32px] p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">

                                <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="font-extrabold text-2xl text-gray-800">{{ $section->name }}</h3>
                                            @if($section->status() == 'Completed')
                                                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Completed</span>
                                            @elseif($section->status() == 'Taken')
                                                <span class="bg-[#FACE68]/20 text-[#FAAC68] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Taken</span>
                                            @elseif($section->status() == 'In Progress')
                                                <span class="bg-[#FACE68]/20 text-[#FAAC68] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">In Progress</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-500 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Not Started</span>
                                            @endif
                                        </div>

                                        <div class="text-gray-500 text-sm font-medium space-x-4 mb-6">
                                            <span class="inline-flex items-center"><span class="mr-1">⏱️</span> {{ $section->duration ?? 'N/A' }} mins</span>
                                            <span class="text-gray-300">|</span>
                                            <span class="inline-flex items-center"><span class="mr-1">🔄</span> Attempts: {{ $section->attemptsLeft() }}/{{ $section->attempt_limit }}</span>
                                        </div>

                                        <div class="flex flex-wrap gap-3">
                                            {{-- TAKE ASSESSMENT BUTTON --}}
                                            @if($section->canAttempt())
                                                <a href="{{ route('student.sections.questions', $section->id) }}"
                                                    class="inline-flex items-center px-6 py-3 bg-[#5A9CB5] hover:bg-[#4A8CA5] text-white font-bold rounded-full shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 no-underline take-assessment"
                                                    data-duration="{{ $section->duration ?? 0 }}">
                                                    📝 Take Assessment
                                                </a>
                                            @else
                                                <button class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-400 font-bold rounded-full cursor-not-allowed shadow-inner"
                                                    disabled>
                                                    🚫 Attempts Used
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Grade and Review Buttons --}}
                                    <div class="w-full md:w-auto flex flex-col items-end gap-3 min-w-[200px]">
                                        @php
                                            $student = App\Models\Student::where('user_id', Auth::id())->first();
                                            $hasAttempts = $student && DB::table('student_attempts')
                                                ->where('student_id', $student->studentID)
                                                ->where('section_id', $section->id)
                                                ->exists();

                                            $highestScore = 0;
                                            $totalMarks = 0;

                                            if ($hasAttempts) {
                                                $highestScore = DB::table('student_attempts')
                                                    ->where('student_id', $student->studentID)
                                                    ->where('section_id', $section->id)
                                                    ->select('attempt_number', DB::raw('SUM(marks_obtained) as total_marks'))
                                                    ->groupBy('attempt_number')
                                                    ->get()
                                                    ->max('total_marks') ?? 0;

                                                $totalMarks = $section->assessments->sum('grade');
                                            }
                                        @endphp

                                        @if($hasAttempts)
                                            {{-- Show Grade if visible --}}
                                            @if($section->grade_visible)
                                                <div class="w-full">
                                                    <div class="px-4 pt-4 py-1 rounded-2xl text-center shadow-inner
                                                                @if($totalMarks > 0 && ($highestScore / $totalMarks) >= 0.7) bg-[#E0F2F1] text-[#5A9CB5]
                                                                @elseif($totalMarks > 0 && ($highestScore / $totalMarks) >= 0.5) bg-[#FFF8E1] text-[#FAAC68]
                                                                @else bg-[#FFEBEE] text-[#FA6868]
                                                                @endif">
                                                        <p class="text-xs font-bold uppercase tracking-wider opacity-70 mb-1">Your Best Score</p>
                                                        <p class="text-3xl font-extrabold">{{ $highestScore }}<span class="text-lg opacity-60">/{{ $totalMarks }}</span></p>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Review Button if enabled --}}
                                            @if($section->review_enabled)
                                                <a href="{{ route('student.review-attempt', $section->id) }}"
                                                    class="w-full text-center px-4 py-2 border-2 border-[#5A9CB5] text-[#5A9CB5] hover:bg-[#5A9CB5] hover:text-white font-bold rounded-full transition-all duration-200 no-underline text-sm">
                                                    🔍 Review Answers
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-gray-400 text-lg font-medium italic">No sections available for this course yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.05)] rounded-[32px] p-8 border border-gray-100">
                    <div class="text-center py-8">
                        <div class="text-6xl mb-4">📭</div>
                        <p class="text-gray-500 text-lg font-bold">You are not enrolled in any courses yet.</p>
                    </div>
                </div>
            @endforelse

        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Confirmation Logic & Filter Logic --}}
    <script>
        function filterCourse(courseId) {
            // Hide all containers
            document.querySelectorAll('.course-container').forEach(el => el.classList.add('hidden'));

            // Show selected container
            const selectedContainer = document.getElementById('course-container-' + courseId);
            if(selectedContainer) selectedContainer.classList.remove('hidden');

            // Update tab styles
            document.querySelectorAll('.course-tab').forEach(btn => {
                if (btn.dataset.courseId == courseId) {
                    // Active state
                    btn.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-50', 'border', 'border-gray-100');
                    btn.classList.add('bg-[#5A9CB5]', 'text-white', 'shadow-md', 'transform', 'scale-105');
                } else {
                    // Inactive state
                    btn.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-50', 'border', 'border-gray-100');
                    btn.classList.remove('bg-[#5A9CB5]', 'text-white', 'shadow-md', 'transform', 'scale-105');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.take-assessment');

            buttons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();

                    const duration = btn.dataset.duration;

                    Swal.fire({
                        title: 'Ready to Start?',
                        text: `This assessment can only be taken for ${duration} minutes.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Start Now!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'rounded-[32px]', // Rounded popup
                            confirmButton: 'bg-[#5A9CB5] hover:bg-[#4A8CA5] text-white font-bold px-6 py-3 rounded-full shadow-lg border-none',
                            cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold px-6 py-3 rounded-full border-none'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = btn.href;
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>