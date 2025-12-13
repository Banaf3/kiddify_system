<!-- resources/views/Module3/questions.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📄 Questions – {{ $course->Title }} - {{ $section->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if($assessments->count() > 0)
            <div class="mb-4 text-red-600 font-bold" id="timer">
                Time Remaining: <span id="time"></span>
            </div>
        @endif

        <form id="assessment-form" action="{{ route('student.sections.questions.submit', $section->id) }}" method="POST">
            @csrf
            @forelse($assessments as $assessment)
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h3 class="font-semibold text-lg mb-4">{{ $assessment->question }}</h3>
                    @if($assessment->image)
                        <img src="{{ asset('storage/' . $assessment->image) }}" alt="Question Image" class="mb-4 max-w-full h-auto">
                    @endif
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="answers[{{ $assessment->id }}]" value="A" class="mr-2" required>
                            {{ $assessment->optionA }}
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="answers[{{ $assessment->id }}]" value="B" class="mr-2" required>
                            {{ $assessment->optionB }}
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="answers[{{ $assessment->id }}]" value="C" class="mr-2" required>
                            {{ $assessment->optionC }}
                        </label>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-700">
                  You have reach the limit attempt 
                </div>
            @endforelse

            @if($assessments->count() > 0)
                <button type="submit" id="submit-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Submit Answers
                </button>
            @endif
        </form>

    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            let duration = {{ $section->duration ?? 0 }} * 60; // duration in seconds
            const timerElement = document.getElementById('time');
            const form = document.getElementById('assessment-form');

            function updateTimer(){
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;
                timerElement.textContent = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;

                if(duration <= 0){
                    clearInterval(timerInterval);

                    // Fancy SweetAlert2 alert
                    Swal.fire({
                        title: '⏰ Time is up!',
                        html: 'Your assessment will be submitted automatically.<br>Click OK to continue.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        form.submit();
                    });
                }

                duration--;
            }

            updateTimer();
            const timerInterval = setInterval(updateTimer, 1000);
        });
    </script>
</x-app-layout>
