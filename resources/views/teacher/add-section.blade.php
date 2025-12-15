<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ $course->Title }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto" x-data="{ showForm: false }">

        {{-- Toggle Button --}}
        <div class="flex justify-end mb-4">
            <button 
                @click="showForm = !showForm" 
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                + Add Assessments
            </button>
        </div>

        {{-- Add Section Form --}}
        <div x-show="showForm" class="mb-6 p-4 border rounded-lg shadow-sm bg-white" x-transition>
            <form action="{{ route('sections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-medium text-gray-700">Section Name</label>
                    <input type="text" name="section_name" required
                        class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <input type="hidden" name="course_id" value="{{ $course->CourseID }}">

                <div>
                    <label class="block font-medium text-gray-700">Date & Time</label>
                    <input type="datetime-local" name="date_time" required
                        class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Duration (minutes)</label>
                    <input type="number" name="duration" min="1" value="60" required
                        class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Number of Attempts</label>
                    <input type="number" name="attempt_limit" min="1" value="1" required
                        class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <p class="text-gray-500 text-sm mt-1">Set how many times a student can attempt this assessment.</p>
                </div>

                <div class="flex items-center space-x-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="review_enabled" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Enable Review (students can view answers after attempt)</span>
                    </label>
                    
                    <label class="flex items-center">
                        <input type="checkbox" name="grade_visible" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Show Grades (students can see their scores)</span>
                    </label>
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Image</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 w-full">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Add Quiz
                    </button>
                </div>
            </form>
        </div>

        {{-- Display Sections --}}
        <div class="space-y-4 mt-6">
            @forelse($sections as $section)
                <div class="flex items-center p-4 border rounded-lg shadow-sm bg-white">

                    {{-- Image --}}
                    <img src="{{ $section->image ? asset('storage/'.$section->image) : asset('images/default_quiz.png') }}"
                        class="w-20 h-20 object-contain rounded-md mr-4">

                    {{-- Details --}}
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">{{ $section->name }}</h3>
                        <p class="text-gray-500 text-sm">
                            {{ \Carbon\Carbon::parse($section->date_time)->format('d M Y, h:i A') }}
                        </p>
                        <p class="text-gray-500 text-xs mt-1">
                            <strong>Duration:</strong> {{ $section->duration ?? 'N/A' }} mins
                            | <strong>Attempts Allowed:</strong> {{ $section->attempt_limit }}
                        </p>

                        {{-- Action Buttons --}}
                        <div class="mt-3 space-x-2">
                            <a href="{{ route('sections.edit', $section->id) }}"
                                class="px-3 py-1 bg-black text-white rounded hover:bg-gray-800 no-underline">
                                Edit
                            </a>

                            <a href="{{ route('teacher.add-questions', ['section' => $section->id, 'course' => $course->CourseID]) }}"
                               class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 no-underline">
                                Add Questions
                            </a>

                            <form action="{{ route('sections.destroy', $section->id) }}"
                                  method="POST"
                                  class="inline-block delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="delete-btn px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    Delete
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center">No sections available.</p>
            @endforelse
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Delete Confirmation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this section?',
                        html: '<p class="text-gray-600">This action cannot be undone.</p>',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    {{-- Success Message --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

</x-app-layout>
