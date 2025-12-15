<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📄 My Assessments
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Course Selection Tabs --}}
        <div class="mb-6 flex space-x-2 overflow-x-auto pb-2">
            @foreach($courses as $course)
                @php
                    $assessmentCount = $course->sections->sum(function ($section) {
                        return $section->assessments->count();
                    });
                @endphp
                <button onclick="filterCourse('{{ $course->CourseID }}')"
                    class="course-tab px-4 py-2 rounded-full border text-sm font-semibold whitespace-nowrap transition-colors
                               {{ $loop->first ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
                    data-course-id="{{ $course->CourseID }}">
                    {{ $course->Title }} <span class="ml-1 opacity-75">({{ $assessmentCount }})</span>
                </button>
            @endforeach
        </div>

        @forelse($courses as $course)
            <div id="course-container-{{ $course->CourseID }}"
                class="course-container mb-8 {{ $loop->first ? '' : 'hidden' }}">
                <h3 class="font-bold text-xl text-indigo-700 mb-4 px-2 border-l-4 border-indigo-500">
                    {{ $course->Title }}
                </h3>

                @forelse($course->sections as $section)
                    <div class="bg-white shadow-md rounded-lg p-4 mb-4 transition hover:shadow-lg">

                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg">{{ $section->name }}</h3>

                                <p class="text-gray-500 text-xs mt-1">
                                    <strong>Duration:</strong> {{ $section->duration ?? 'N/A' }} minutes
                                    |
                                    <strong>Status:</strong> {{ $section->status() }}
                                    |
                                    <strong>Attempts Left:</strong>
                                    {{ $section->attemptsLeft() }}/{{ $section->attempt_limit }}
                                </p>

                                <div class="mt-3 flex gap-2">
                                    {{-- TAKE ASSESSMENT BUTTON --}}
                                    @if($section->canAttempt())
                                        <a href="{{ route('student.sections.questions', $section->id) }}"
                                            class="inline-block px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded no-underline take-assessment"
                                            data-duration="{{ $section->duration ?? 0 }}">
                                            Take Assessment
                                        </a>
                                    @else
                                        <button class="inline-block px-4 py-2 bg-gray-400 text-gray-200 rounded cursor-not-allowed"
                                            disabled>
                                            Attempts Used
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Grade and Review Buttons --}}
                            <div class="flex flex-col gap-2 ml-4">
                                @php
                                    // Optimization: Move this query out if possible, but keeping it here to match reference exactly
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
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                                                @if($totalMarks > 0 && ($highestScore / $totalMarks) >= 0.7) bg-green-100 text-green-800
                                                                @elseif($totalMarks > 0 && ($highestScore / $totalMarks) >= 0.5) bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800
                                                                @endif">
                                                📊 {{ $highestScore }}/{{ $totalMarks }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Review Button if enabled --}}
                                    @if($section->review_enabled)
                                        <a href="{{ route('student.review-attempt', $section->id) }}"
                                            class="inline-flex items-center px-3 py-1 bg-purple-500 hover:bg-purple-600 text-white text-sm rounded no-underline">
                                            📝 Review Answers
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>

                    </div>
                @empty
                    <p class="text-gray-500 ml-4 mb-4 italic">No sections available for this course yet.</p>
                @endforelse
            </div>
        @empty
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    <p class="text-gray-500 text-lg">You are not enrolled in any courses yet.</p>
                </div>
            </div>
        @endforelse

    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Confirmation Logic & Filter Logic --}}
    <script>
        function filterCourse(courseId) {
            // Hide all containers
            document.querySelectorAll('.course-container').forEach(el => el.classList.add('hidden'));

            // Show selected container
            document.getElementById('course-container-' + courseId).classList.remove('hidden');

            // Update tab styles
            document.querySelectorAll('.course-tab').forEach(btn => {
                if (btn.dataset.courseId == courseId) {
                    btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-50');
                    btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
                } else {
                    btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-50');
                    btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
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
                            confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded',
                            cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded'
                        }
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