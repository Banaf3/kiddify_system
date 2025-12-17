<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#5A9CB5] leading-tight">
            {{ $course->Title }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto" x-data="{ showForm: false }">

        {{-- Top Actions --}}
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('teacher.assessments') }}"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 no-underline transition-colors">
                &larr; Back
            </a>

            <button @click="showForm = !showForm"
                class="px-4 py-2 bg-[#5A9CB5] text-white rounded hover:bg-[#4A8CA5] transition-colors">
                + Add Assessments
            </button>
        </div>

        {{-- Add Section Form --}}
        <div x-show="showForm" class="mb-6 p-6 border rounded-2xl shadow-lg bg-white" x-transition>
            <form action="{{ route('sections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-medium text-gray-700">Section Name</label>
                    <input type="text" name="section_name" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all">
                </div>

                <input type="hidden" name="course_id" value="{{ $course->CourseID }}">

                <div>
                    <label class="block font-medium text-gray-700">Date & Time</label>
                    <input type="datetime-local" name="date_time" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all">
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Duration (minutes)</label>
                    <input type="number" name="duration" min="1" value="60" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all">
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Number of Attempts</label>
                    <input type="number" name="attempt_limit" min="1" value="1" required
                        class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all">
                    <p class="text-gray-500 text-sm mt-1">Set how many times a student can attempt this assessment.</p>
                </div>

                <div class="flex items-center space-x-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="review_enabled" value="1"
                            class="rounded border-gray-300 text-[#5A9CB5] shadow-sm focus:border-[#5A9CB5] focus:ring focus:ring-[#5A9CB5] focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Enable Review (students can view answers after
                            attempt)</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="grade_visible" value="1"
                            class="rounded border-gray-300 text-[#5A9CB5] shadow-sm focus:border-[#5A9CB5] focus:ring focus:ring-[#5A9CB5] focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Show Grades (students can see their scores)</span>
                    </label>
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Image</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-[#5A9CB5]/10 file:text-[#5A9CB5]
                        hover:file:bg-[#5A9CB5]/20
                    ">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-[#5A9CB5] text-white rounded-lg hover:bg-[#4A8CA5] transition-colors font-bold shadow-md">
                        Add Quiz
                    </button>
                </div>
            </form>
        </div>

        {{-- Display Sections --}}
        <div class="space-y-4 mt-6">
            @forelse($sections as $section)
                <div
                    class="flex items-center p-4 border border-gray-100 rounded-[20px] shadow-sm bg-white hover:shadow-md transition-shadow">

                    {{-- Image --}}
                    <img src="{{ $section->image ? asset('storage/' . $section->image) : asset('images/default_quiz.png') }}"
                        class="w-20 h-20 object-contain rounded-xl mr-4 bg-gray-50">

                    {{-- Details --}}
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-gray-800">{{ $section->name }}</h3>
                        <p class="text-gray-500 text-sm font-medium">
                            📅 {{ \Carbon\Carbon::parse($section->date_time)->format('d M Y, h:i A') }}
                        </p>
                        <p class="text-gray-500 text-xs mt-1">
                            <span class="bg-[#FACE68]/20 text-[#D9A640] px-2 py-0.5 rounded mr-2">⏱
                                {{ $section->duration ?? 'N/A' }} mins</span>
                            <span class="bg-[#5A9CB5]/10 text-[#5A9CB5] px-2 py-0.5 rounded">🔄
                                {{ $section->attempt_limit }} Attempts</span>
                        </p>

                        {{-- Action Buttons --}}
                        <div class="mt-3 flex space-x-2">
                            <a href="{{ route('sections.edit', $section->id) }}"
                                class="px-3 py-1 bg-gray-800 text-white rounded-lg hover:bg-gray-900 no-underline text-sm flex items-center">
                                ✏️ Edit
                            </a>

                            <a href="{{ route('teacher.add-questions', ['section' => $section->id, 'course' => $course->CourseID]) }}"
                                class="px-3 py-1 bg-[#FACE68] text-white rounded-lg hover:bg-[#F0C058] no-underline text-sm font-bold flex items-center shadow-sm">
                                ➕ Questions
                            </a>

                            <form action="{{ route('sections.destroy', $section->id) }}" method="POST"
                                class="inline-block delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="delete-btn px-3 py-1 bg-[#FA6868] text-white rounded-lg hover:bg-[#E05050] text-sm flex items-center font-bold">
                                    🗑 Delete
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-gray-50 rounded-[32px] border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-medium">No sections available yet.</p>
                </div>
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
                        confirmButtonColor: '#FA6868',
                        cancelButtonColor: '#9CA3AF',
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
                showConfirmButton: false,
                confirmButtonColor: '#5A9CB5'
            });
        </script>
    @endif

</x-app-layout>