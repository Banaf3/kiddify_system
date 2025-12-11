<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📖 {{ $course->Title }} </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <p><strong>Teacher:</strong> {{ $course->teacher->name ?? 'N/A' }}</p>
                <p><strong>Days:</strong> {{ implode(', ', json_decode($course->days, true)) }}</p>
                <p><strong>Time:</strong> {{ $course->Start_time }} - {{ $course->end_time }}</p>
                <p><strong>Total Students:</strong> {{ $course->students->count() }}</p>
            </div>

            <a href="{{ route('student.courses.index') }}" class="inline-block mt-4 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">⬅️ Back</a>
        </div>
    </div>
</x-app-layout>

