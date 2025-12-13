<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">
            Add Questions – {{ $section->name }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 space-y-6">

        {{-- 🔥 Floating Add Button --}}
        <button id="toggleFormBtn"
            class="fixed bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center text-3xl">
            +
        </button>

        {{-- 🔥 Hidden Add Form --}}
       {{-- 🔥 Hidden Add Form --}}
<div id="questionForm"
     class="hidden bg-white p-4 rounded shadow border border-gray-200">

    <h3 class="text-lg font-semibold mb-3">Add New Question</h3>

    <form method="POST"
          action="{{ route('questions.store', $section->id) }}"
          enctype="multipart/form-data"
          class="space-y-2">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">
                Assessment Image (Optional)
            </label>
            <input type="file"
                   name="image"
                   class="block w-full border rounded p-2">
        </div>


        <input name="question"
               class="w-full border rounded p-2"
               placeholder="Question"
               required>

        <input name="optionA"
               class="w-full border rounded p-2"
               placeholder="Option A"
               required>

        <input name="optionB"
               class="w-full border rounded p-2"
               placeholder="Option B"
               required>

        <input name="optionC"
               class="w-full border rounded p-2"
               placeholder="Option C"
               required>

        <select name="correct_answer"
                class="w-full border rounded p-2"
                required>
            <option value="">Correct Answer</option>
            <option value="A">Option A</option>
            <option value="B">Option B</option>
            <option value="C">Option C</option>
        </select>

        
        <input name="grade"
               type="number"
               min="0"
               class="w-full border rounded p-2"
               placeholder="Grade / Points"
               required>

        <button class="bg-blue-500 text-white px-4 py-2 rounded w-full">
            Add Question
        </button>
    </form>
</div>


        {{-- 🔥 QUESTIONS LIST --}}
@foreach($questions as $q)
    <div class="bg-white p-4 rounded shadow">
           {{-- 🖼️ Question Image (Optional) --}}
       @if($q->image)
    <div class="mb-4 flex justify-center">
        <img src="{{ asset('storage/' . $q->image) }}"
             alt="Question Image"
             class="w-[600px] h-[400px] object-contain rounded-lg border">
    </div>
@endif


        {{-- 🔢 Question Number --}}
        <p class="font-bold text-lg mb-1">
            {{ $loop->iteration }}. {{ $q->question }}
        </p>
          
        <ul class="text-sm text-gray-600 mb-2">
            <li>A. {{ $q->optionA }}</li>
            <li>B. {{ $q->optionB }}</li>
            <li>C. {{ $q->optionC }}</li>
        </ul>

      

        {{-- ✅ Correct Answer & Grade --}}
        <p class="flex justify-between text-green-600 mt-1">
            <span>
                Correct Answer: {{ $q->correct_answer }}
            </span>

            <span>
                Grade: {{ $q->grade }}
            </span>
        </p>

        {{-- Edit and Delete Buttons --}}
        <div class="mt-3 flex gap-2">
            <a href="{{ route('questions.edit', ['assessment' => $q->AssessmentID]) }}"
               class="px-3 py-1 bg-black text-white rounded no-underline">
                Edit
            </a>

            <form method="POST" action="{{ route('questions.destroy', $q->AssessmentID) }}">
                @csrf
                @method('DELETE')
                <button type="button" class="delete-btn px-3 py-1 bg-red-500 text-white rounded">
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

        btn.addEventListener('click', () => {
            form.classList.toggle('hidden');
            form.classList.add('transition-all', 'duration-300');
        });
    </script>

  
    {{-- Delete Confirmation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Delete this Questions?',
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
{{-- 🔥 Beautiful Success Popup (No loading icon) --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success 🎉',
        html: `<b>{{ session('success') }}</b>`,
        timer: 2500,
        showConfirmButton: false,
        backdrop: `rgba(0,0,0,0.4)`
    });
</script>
@endif


</x-app-layout>
