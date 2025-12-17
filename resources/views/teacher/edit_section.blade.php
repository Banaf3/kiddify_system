<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold">Edit Section</h2>
        <p class="text-gray-500 text-sm mt-1">Update quiz section details</p>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">

        {{-- Back Button --}}
        <div class="mb-4">
            <a href="{{ route('teacher.course.sections', $section->CourseID) }}"
               class="text-blue-500 hover:underline">
                ← Back to Sections
            </a>
        </div>

        {{-- Edit Form --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <form action="{{ route('sections.update', $section->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-4">

                @csrf
                @method('PUT')

                {{-- Section Name --}}
                <div>
                    <label class="block text-gray-700 font-medium">Section Name</label>
                    <input type="text"
                           name="section_name"
                           value="{{ old('section_name', $section->name) }}"
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                {{-- Date & Time --}}
                <div>
                    <label class="block text-gray-700 font-medium">Date & Time</label>
                    <input type="datetime-local"
                           name="date_time"
                           value="{{ old('date_time', \Carbon\Carbon::parse($section->date_time)->format('Y-m-d\TH:i')) }}"
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                {{-- Duration --}}
                <div>
                    <label class="block text-gray-700 font-medium">Duration (minutes)</label>
                    <input type="number"
                           name="duration"
                           value="{{ old('duration', $section->duration) }}"
                           min="1"
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                {{-- Limit Attempt --}}
                <div>
                    <label class="block text-gray-700 font-medium">Limit Attempt</label>
                    <input type="number"
                           name="attempt_limit"
                           value="{{ old('attempt_limit', $section->attempt_limit) }}"
                           min="1"
                           class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                {{-- Review and Grade Settings --}}
                <div class="flex items-center space-x-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="review_enabled" 
                               value="1" 
                               {{ old('review_enabled', $section->review_enabled) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Enable Review (students can view answers after attempt)</span>
                    </label>
                    
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="grade_visible" 
                               value="1" 
                               {{ old('grade_visible', $section->grade_visible) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Show Grades (students can see their scores)</span>
                    </label>
                </div>

                {{-- Current Image --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Current Image</label>
                    @if($section->image)
                        <img src="{{ asset('storage/' . $section->image) }}"
                             class="w-32 h-32 object-contain rounded border">
                    @else
                        <p class="text-gray-400 text-sm">No image uploaded</p>
                    @endif
                </div>

                {{-- Upload New Image --}}
                <div>
                    <label class="block text-gray-700 font-medium">Change Image (optional)</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 w-full">
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('teacher.course.sections', $section->CourseID) }}"
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Update Section
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
