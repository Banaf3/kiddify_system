<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-[#5A9CB5] leading-tight">📚 Course Management</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.courses.create') }}"
                    class="inline-block px-4 py-2 bg-[#5A9CB5] text-white font-semibold rounded-lg hover:bg-[#4A8CA5] no-underline shadow-md">
                    Add New Course
                </a>
            </div>

            {{-- Success message --}}
            @if (session('success'))
                <div id="success-message"
                    class="mb-4 p-4 bg-[#FACE68]/20 border border-[#FAAC68] text-[#FAAC68] rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <div id="error-messages" class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div
                class="bg-white shadow-[0_10px_30px_rgba(90,156,181,0.1)] rounded-[20px] overflow-hidden border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#FACE68]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Title</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Teacher</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Days</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Time</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Total Students</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($courses as $course)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $course->Title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ $course->teacher && $course->teacher->user ? $course->teacher->user->name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ implode(', ', json_decode($course->days, true) ?? []) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $course->Start_time }} -
                                    {{ $course->end_time }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-[#5A9CB5]/10 text-[#5A9CB5] px-2 py-1 rounded-md text-xs font-bold">
                                        {{ $course->students->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                    <a href="{{ route('admin.courses.edit', $course->CourseID) }}"
                                        class="px-3 py-1 bg-[#5A9CB5] text-white rounded hover:bg-[#4A8CA5] no-underline shadow-sm font-medium">Edit</a>
                                    <a href="{{ route('admin.courses.students', $course->CourseID) }}"
                                        class="px-3 py-1 bg-[#FACE68] text-gray-800 rounded hover:bg-[#E5BD58] no-underline shadow-sm font-medium">Students</a>
                                    <form action="{{ route('admin.courses.destroy', $course->CourseID) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Warning: Are you sure you want to delete this course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="px-3 py-1 bg-[#FA6868] text-white rounded hover:bg-[#E05050] no-underline shadow-sm font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- JS to auto-hide messages after 3 seconds --}}
    <script>
        setTimeout(() => {
            const success = document.getElementById('success-message');
            if (success) success.style.display = 'none';

            const errors = document.getElementById('error-messages');
            if (errors) errors.style.display = 'none';
        }, 3000); // 3 seconds
    </script>
</x-app-layout>