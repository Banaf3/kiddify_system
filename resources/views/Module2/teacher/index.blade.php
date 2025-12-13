<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📚 My Courses</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($courses->isEmpty())
                <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-700">
                    You have not been assigned any courses yet.
                </div>
            @else
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-yellow-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Course Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Days</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total Students</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($courses as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $course->Title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ !empty($course->days) ? implode(', ', json_decode($course->days, true)) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $course->Start_time }} - {{ $course->end_time }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $course->students->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                    <a href="{{ route('teacher.courses.show', $course->CourseID) }}" 
                                       class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 no-underline" >
                                       View Students
                                    </a>
                                   
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

