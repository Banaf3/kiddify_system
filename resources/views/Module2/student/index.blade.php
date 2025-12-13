<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📚 My Courses</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($courses->isEmpty())
                <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-700">
                    You are not enrolled in any courses yet.
                </div>
            @else
                {{-- View Timetable Button --}}
                <div class="mb-4 text-right">
                    <a href="{{ route('student.courses.timetable') }}" 
                       class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 no-underline">
                        View Timetable
                    </a>
                </div>

                {{-- Courses Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($courses as $course)
        <a href="{{ route('student.courses.sections', $course->CourseID) }}" class="block">
            <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300 p-4">
                <h3 class="font-semibold text-lg">{{ $course->Title }}</h3>
                <p class="text-gray-600 text-sm mt-2">{{ $course->description ?? 'No description available.' }}</p>
                <p class="text-gray-500 text-xs mt-1"><strong>Teacher:</strong> {{ $course->teacher->user->name ?? 'N/A' }}</p>
            </div>
        </a>
    @endforeach
</div>

            @endif

        </div>
    </div>
</x-app-layout>




