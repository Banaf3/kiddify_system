<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-[#5A9CB5] leading-tight">Edit Course</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-[0_10px_30px_rgba(90,156,181,0.1)] rounded-[32px] border border-gray-100 p-8">

                <!-- Display schedule conflict or max student error -->
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="error-message mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif

                <form action="{{ route('admin.courses.update', $course->CourseID) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Course Title -->
                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-2">Course Title</label>
                        <input type="text" name="Title" value="{{ old('Title', $course->Title) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-[#5A9CB5] focus:ring-[#5A9CB5]">
                    </div>

                    <!-- Course Description -->
                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-2">Course Description</label>
                        <textarea name="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-[#5A9CB5] focus:ring-[#5A9CB5]">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <!-- Teacher Dropdown -->
                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-2">Teacher</label>
                        <select name="teachersID" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-[#5A9CB5] focus:ring-[#5A9CB5]">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->teachersID }}" 
                                    {{ $teacher->teachersID == old('teachersID', $course->teachersID) ? 'selected' : '' }}>
                                    {{ $teacher->user->name ?? 'Unnamed Teacher' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Days Checkboxes -->
                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-2">Days</label>
                        <div class="flex flex-wrap gap-3 mt-1">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <label class="inline-flex items-center bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 cursor-pointer hover:bg-blue-50 transition-colors">
                                    <input type="checkbox" name="days[]" value="{{ $day }}"
                                        {{ is_array(old('days', $selectedDays)) && in_array($day, old('days', $selectedDays)) ? 'checked' : '' }}
                                        class="mr-2 text-[#5A9CB5] focus:ring-[#5A9CB5] rounded">
                                    <span class="text-gray-700">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Start & End Times -->
                    <div class="mb-4 flex gap-4">
                        <div class="flex-1">
                            <label class="block font-bold text-gray-700 mb-2">Start Time</label>
                            <input type="time" name="Start_time" value="{{ old('Start_time', $course->Start_time) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-[#5A9CB5] focus:ring-[#5A9CB5]">
                        </div>
                        <div class="flex-1">
                            <label class="block font-bold text-gray-700 mb-2">End Time</label>
                            <input type="time" name="end_time" value="{{ old('end_time', $course->end_time) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-[#5A9CB5] focus:ring-[#5A9CB5]">
                        </div>
                    </div>

                    <!-- Assign Students -->
                    <div class="mb-4">
                        <label class="block font-bold text-gray-700 mb-2">Assign Students</label>
                        <select id="students-select" name="student_ids[]" multiple
                                class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:border-[#5A9CB5] focus:ring-[#5A9CB5]">
                            @foreach($students as $student)
                                <option value="{{ $student->studentID }}" 
                                    {{ is_array(old('student_ids', $selectedStudents)) && in_array($student->studentID, old('student_ids', $selectedStudents)) ? 'selected' : '' }}>
                                    {{ $student->user->name ?? 'Unnamed Student' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 mt-8">
                        <a href="{{ route('admin.courses.index') }}"
                           class="px-6 py-2 bg-gray-200 rounded-lg text-gray-700 font-bold hover:bg-gray-300 transition-colors">Cancel</a>
                        <button type="submit"
                                class="px-6 py-2 bg-[#5A9CB5] text-white rounded-lg font-bold hover:bg-[#4A8CA5] shadow-md transition-all">Update Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tom Select JS & CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <script>
        // Initialize Tom Select
        new TomSelect("#students-select", {
            plugins: ['checkbox_options'],
            placeholder: "Select students...",
            maxItems: null
        });

        // AUTO-HIDE ERROR MESSAGES after 3 seconds
        document.addEventListener("DOMContentLoaded", () => {
            const errors = document.querySelectorAll(".error-message");
            errors.forEach(el => {
                setTimeout(() => {
                    el.style.transition = "opacity 0.5s ease";
                    el.style.opacity = "0";
                    setTimeout(() => el.remove(), 500); // remove after fade
                }, 3000); // 3 seconds
            });
        });
    </script>
</x-app-layout>



