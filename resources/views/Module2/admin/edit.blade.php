<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> Edit Course</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('admin.courses.update', $course->CourseID) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Course Title</label>
                        <input type="text" name="Title" value="{{ old('Title', $course->Title) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('Title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Teacher</label>
                        <select name="teachersID" class="mt-1 block w-full border-gray-300 rounded-md">
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->teachersID }}" {{ $teacher->teachersID == $course->teachersID ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teachersID') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Days</label>
                        <select name="days[]" multiple class="mt-1 block w-full border-gray-300 rounded-md">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <option value="{{ $day }}" {{ in_array($day, $selectedDays) ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                        @error('days') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4 flex gap-4">
                        <div>
                            <label class="block font-medium text-gray-700">Start Time</label>
                            <input type="time" name="Start_time" value="{{ $course->Start_time }}" class="mt-1 block border-gray-300 rounded-md">
                            @error('Start_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700">End Time</label>
                            <input type="time" name="end_time" value="{{ $course->end_time }}" class="mt-1 block border-gray-300 rounded-md">
                            @error('end_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-gray-700">Assign Students</label>
                        <select name="student_ids[]" multiple class="mt-1 block w-full border-gray-300 rounded-md">
                            @foreach($students as $student)
                                <option value="{{ $student->studentID }}" {{ in_array($student->studentID, $selectedStudents) ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
