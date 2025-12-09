<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">
            Add Questions – {{ $section->name }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 space-y-6">

        {{-- ADD QUESTION FORM --}}
        <form method="POST" action=""
              class="bg-white p-6 rounded shadow space-y-4">
            @csrf

            <input name="question" class="w-full border rounded p-2" placeholder="Question" required>

            <input name="option_a" class="w-full border rounded p-2" placeholder="Option A" required>
            <input name="option_b" class="w-full border rounded p-2" placeholder="Option B" required>
            <input name="option_c" class="w-full border rounded p-2" placeholder="Option C" required>
            <input name="option_d" class="w-full border rounded p-2" placeholder="Option D" required>

            <select name="correct_answer" class="w-full border rounded p-2" required>
                <option value="">Correct Answer</option>
                <option value="A">Option A</option>
                <option value="B">Option B</option>
                <option value="C">Option C</option>
                <option value="D">Option D</option>
            </select>

            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                Add Question
            </button>
        </form>

      {{-- QUESTIONS LIST --}}
        @foreach($questions as $q)
            <div class="bg-white p-4 rounded shadow">
                <p class="font-semibold">{{ $q->question }}</p>
                <ul class="text-sm text-gray-600">
                    <li>A. {{ $q->option_a }}</li>
                    <li>B. {{ $q->option_b }}</li>
                    <li>C. {{ $q->option_c }}</li>
                    <li>D. {{ $q->option_d }}</li>
                </ul>
                <p class="text-green-600 mt-1">
                    Correct: {{ $q->correct_answer }}
                </p>

                <div class="mt-3 flex gap-2">
                    <a href="{{ route('questions.edit', $q->id) }}"
                       class="px-3 py-1 bg-black text-white rounded">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('questions.destroy', $q->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 bg-red-500 text-white rounded">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
</x-app-layout>
