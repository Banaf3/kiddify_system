<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Course</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf

                    <!-- Course Title -->
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Course Title</label>
                        <input type="text" name="Title" value="{{ old('Title') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('Title')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
    <label class="block font-medium text-gray-700">Course Description</label>
    <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
    @error('description')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>


                    <!-- Teacher Dropdown -->
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Teacher</label>
                        <select name="teachersID" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->teachersID }}"
                                    {{ old('teachersID') == $teacher->teachersID ? 'selected' : '' }}>
                                    {{ $teacher->user->name ?? 'Unnamed Teacher' }}
                                </option>
                            @endforeach
                        </select>
                        @error('teachersID')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Days Checkboxes -->
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Days</label>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="days[]" value="{{ $day }}"
                                        {{ is_array(old('days')) && in_array($day, old('days')) ? 'checked' : '' }}
                                        class="mr-2">
                                    {{ $day }}
                                </label>
                            @endforeach
                        </div>
                        @error('days')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Start and End Time -->
                    <div class="mb-4 flex gap-4">
                        <div class="flex-1">
                            <label class="block font-medium text-gray-700">Start Time</label>
                            <input type="time" name="Start_time" value="{{ old('Start_time') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md">
                            @error('Start_time')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex-1">
                            <label class="block font-medium text-gray-700">End Time</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md">
                            @error('end_time')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
<div class="mb-4">
    <label class="block font-medium text-gray-700">Max Students</label>
    <input type="number" name="maxStudent" value="{{ old('maxStudent', 10) }}"
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" min="1">
    @error('maxStudent')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>
                    <!-- Students Select with Checkboxes (Tom Select) -->
                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Assign Students</label>
                        <select id="students-select" name="student_ids[]" multiple class="mt-1 block w-full border-gray-300 rounded-md">
                            @foreach($students as $student)
                                <option value="{{ $student->studentID }}"
                                    {{ is_array(old('student_ids')) && in_array($student->studentID, old('student_ids')) ? 'selected' : '' }}>
                                    {{ $student->user->name ?? 'Unnamed Student' }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_ids')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.courses.index') }}"
                           class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
                        <button type="submit"
                                class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Tom Select JS & CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <script>
        new TomSelect("#students-select", {
            plugins: ['checkbox_options'], // checkbox plugin
            placeholder: "Select students...",
            maxItems: null
        });
    </script>

</x-app-layout>



