<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📚 Sections – {{ $course->Title }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Back Button --}}
        <a href="{{ route('student.courses.assessments', $course->CourseID) }}"
           class="inline-block mb-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded text-black no-underline">
            ← Back to Courses
        </a>

        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Sections --}}
        @forelse($sections as $section)
            <div class="bg-white shadow-md rounded-lg p-4 mb-4">

                <h3 class="font-semibold text-lg">{{ $section->name }}</h3>

                <p class="text-gray-500 text-xs mt-1">
                    <strong>Duration:</strong> {{ $section->duration ?? 'N/A' }} minutes
                    |
                    <strong>Status:</strong> {{ $section->status() }}
                    |
                    <strong>Attempts Left:</strong>
                    {{ $section->attemptsLeft() }}/{{ $section->attempt_limit }}
                </p>

                {{-- TAKE ASSESSMENT BUTTON --}}
                @if($section->canAttempt())
                    <a href="{{ route('student.sections.questions', $section->id) }}"
                       class="inline-block mt-3 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded no-underline take-assessment"
                       data-duration="{{ $section->duration ?? 0 }}">
                        Take Assessment
                    </a>
                @else
                    <button
                        class="inline-block mt-3 px-4 py-2 bg-gray-400 text-gray-200 rounded cursor-not-allowed"
                        disabled>
                        Attempts 
                    </button>
                @endif

            </div>
        @empty
            <p class="text-gray-500">No sections available for this course yet.</p>
        @endforelse

    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Confirmation Logic --}}
    <script>
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
