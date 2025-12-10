<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📚 Course Management</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
    <a href="{{ route('admin.courses.create') }}" 
       class="inline-block px-4 py-2 bg-purple-600 text-black font-semibold rounded-lg hover:bg-purple-700">
        ➕ Add New Course
    </a>
</div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-yellow-300">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Teacher</th>
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
                            <td class="px-6 py-4 whitespace-nowrap">{{ $course->teacher->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ implode(', ', json_decode($course->days, true)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $course->Start_time }} - {{ $course->end_time }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $course->students->count() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                <a href="{{ route('admin.courses.edit', $course->CourseID) }}" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">✏️ Edit</a>
                                <a href="{{ route('admin.courses.students', $course->CourseID) }}" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">👥 Students</a>
                                <form action="{{ route('admin.courses.destroy', $course->CourseID) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">🗑️ Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
