<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#5A9CB5]">
            Add Questions – {{ $section->name }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 space-y-6">

        {{-- Back Button --}}
        <div>
            <a href="{{ route('teacher.course.sections', $section->CourseID) }}" 
               class="inline-block px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 no-underline transition-colors">
               &larr; Back to Sections
            </a>
        </div>

        {{-- 🔥 Floating Add Button --}}
        <button id="toggleFormBtn"
            class="fixed bottom-6 right-6 bg-[#5A9CB5] hover:bg-[#4A8CA5] text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center text-3xl z-50 transition-colors">
            +
        </button>

        {{-- 🔥 Hidden Add Form --}}
        <div id="questionForm" class="hidden bg-white p-6 rounded-2xl shadow-lg border border-gray-100">

            <h3 class="text-lg font-bold mb-3 text-gray-800">Add New Question</h3>

            <form method="POST" action="{{ route('questions.store', $section->id) }}" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">
                        Assessment Image (Optional)
                    </label>
                    <input type="file" name="image" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-[#5A9CB5]/10 file:text-[#5A9CB5]
                        hover:file:bg-[#5A9CB5]/20
                    ">
                </div>


                <input name="question" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all" placeholder="Question" required>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input name="optionA" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all" placeholder="Option A" required>
                    <input name="optionB" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all" placeholder="Option B" required>
                    <input name="optionC" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all" placeholder="Option C" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <select name="correct_answer" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all" required>
                        <option value="">Select Correct Answer</option>
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                    </select>

                    <input name="grade" type="number" min="0" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-[#5A9CB5] focus:border-[#5A9CB5] outline-none transition-all" placeholder="Grade / Points"
                        required>
                </div>

                <button class="bg-[#5A9CB5] hover:bg-[#4A8CA5] text-white px-4 py-2 rounded-lg w-full font-bold transition-colors shadow-md">
                    Add Question
                </button>
            </form>
        </div>


        {{-- 🔥 QUESTIONS LIST --}}
        @foreach($questions as $q)
            <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                {{-- 🖼️ Question Image (Optional) --}}
                @if($q->image)
                    <div class="mb-4 flex justify-center">
                        <img src="{{ asset('storage/' . $q->image) }}" alt="Question Image"
                            class="max-h-[300px] object-contain rounded-xl border border-gray-100">
                    </div>
                @endif


                {{-- 🔢 Question Number --}}
                <p class="font-bold text-lg mb-2 text-gray-800">
                    <span class="bg-[#5A9CB5]/10 text-[#5A9CB5] px-2 py-1 rounded-lg mr-2">{{ $loop->iteration }}.</span> {{ $q->question }}
                </p>

                <ul class="text-sm text-gray-600 mb-4 space-y-1 ml-2 pl-4 border-l-2 border-gray-100">
                    <li><span class="font-bold text-gray-400 mr-2">A.</span> {{ $q->optionA }}</li>
                    <li><span class="font-bold text-gray-400 mr-2">B.</span> {{ $q->optionB }}</li>
                    <li><span class="font-bold text-gray-400 mr-2">C.</span> {{ $q->optionC }}</li>
                </ul>

                {{-- ✅ Correct Answer & Grade --}}
                <div class="flex justify-between items-center text-sm font-bold bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <span class="text-[#5A9CB5]">
                        ✅ Correct: {{ $q->correct_answer }}
                    </span>

                    <span class="text-[#FACE68] bg-[#FACE68]/10 px-3 py-1 rounded-full">
                        ⭐ {{ $q->grade }} Pts
                    </span>
                </div>

                {{-- Edit and Delete Buttons --}}
                <div class="mt-4 flex gap-2 justify-end">
                    <a href="{{ route('questions.edit', ['assessment' => $q->AssessmentID]) }}"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg no-underline hover:bg-gray-700 text-sm font-bold shadow-sm transition-colors">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('questions.destroy', $q->AssessmentID) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="delete-btn px-4 py-2 bg-[#FA6868] text-white rounded-lg hover:bg-[#E05050] text-sm font-bold shadow-sm transition-colors">
                            Delete
                        </button>
                    </form>
                </div>

            </div>
        @endforeach

    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 🔥 Toggle Form Animation --}}
    <script>
        const btn = document.getElementById('toggleFormBtn');
        const form = document.getElementById('questionForm');

        if(btn && form) {
            btn.addEventListener('click', () => {
                form.classList.toggle('hidden');
            });
        }
    </script>


    {{-- Delete Confirmation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this Question?',
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
    {{-- 🔥 Beautiful Success Popup (No loading icon) --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success 🎉',
                html: `<b>{{ session('success') }}</b>`,
                timer: 2500,
                showConfirmButton: false,
                confirmButtonColor: '#5A9CB5',
                backdrop: `rgba(0,0,0,0.4)`
            });
        </script>
    @endif


</x-app-layout>