<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">
            Edit Question – {{ $assessment->question }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 space-y-6">
        {{-- Display errors --}}
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- EDIT QUESTION FORM --}}
        <form method="POST" action="{{ route('questions.update', $assessment->AssessmentID) }}"
              class="bg-white p-6 rounded shadow space-y-4">
            @csrf
            @method('PUT')

            <input name="question" class="w-full border rounded p-2" placeholder="Question"
                   value="{{ old('question', $assessment->question) }}" required>

            <input name="optionA" class="w-full border rounded p-2" placeholder="Option A"
                   value="{{ old('optionA', $assessment->optionA) }}" required>
            <input name="optionB" class="w-full border rounded p-2" placeholder="Option B"
                   value="{{ old('optionB', $assessment->optionB) }}" required>
            <input name="optionC" class="w-full border rounded p-2" placeholder="Option C"
                   value="{{ old('optionC', $assessment->optionC) }}" required>

            <select name="correct_answer" class="w-full border rounded p-2" required>
                <option value="">Correct Answer</option>
                <option value="A" {{ old('correct_answer', $assessment->correct_answer) == 'A' ? 'selected' : '' }}>Option A</option>
                <option value="B" {{ old('correct_answer', $assessment->correct_answer) == 'B' ? 'selected' : '' }}>Option B</option>
                <option value="C" {{ old('correct_answer', $assessment->correct_answer) == 'C' ? 'selected' : '' }}>Option C</option>
            </select>

            <input name="grade" type="number" min="0" class="w-full border rounded p-2" placeholder="Grade/Points"
                   value="{{ old('grade', $assessment->grade) }}" required>

            <button class="bg-green-500 text-white px-4 py-2 rounded">
                Update Question
            </button>
        </form>
    </div>
</x-app-layout>
