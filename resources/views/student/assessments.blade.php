<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📄 Assessments – {{ $course->Title }}</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <a href="{{ route('student.courses.index') }}"
           class="inline-block mb-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded text-black">
           ← Back to Courses
        </a>

        @forelse($sections as $section)
            <div class="bg-white shadow-md rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-2">Section: {{ $section->name }}</h3>

                @forelse($section->assessments as $assessment)
                    <div class="bg-gray-50 p-3 rounded mb-3 border">
                        @if($assessment->image)
                            <img src="{{ asset('storage/' . $assessment->image) }}"
                                 class="w-full h-auto rounded border mb-2">
                        @endif
                        <p class="font-semibold">{{ $assessment->question }}</p>
                        <ul class="text-sm text-gray-600 mt-1">
                            <li>A. {{ $assessment->optionA }}</li>
                            <li>B. {{ $assessment->optionB }}</li>
                            <li>C. {{ $assessment->optionC }}</li>
                        </ul>
                        <p class="flex justify-between text-green-600 mt-1">
                            <span>Correct Answer: {{ $assessment->correct_answer }}</span>
                            <span>Grade: {{ $assessment->grade }}</span>
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No assessments assigned for this section yet.</p>
                @endforelse
            </div>
        @empty
            <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-700">
                No sections available for this course.
            </div>
        @endforelse

    </div>
</x-app-layout>
