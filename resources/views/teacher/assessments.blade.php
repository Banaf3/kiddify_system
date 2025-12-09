<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold">STEM For Kids</h2>
        <p class="text-gray-500 text-sm mt-1">Science, Technology, English & Mathematics</p>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto" x-data="{ showForm: false }">

        {{-- Add New Quiz Button --}}
        <div class="flex justify-end mb-4">
            <button @click="showForm = !showForm" class="w-10 h-10 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>

        {{-- Add Quiz Form --}}
        <div x-show="showForm" class="mb-6 p-4 border rounded-lg shadow-sm bg-white" x-transition>
            <form action="{{ route('sections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-700 font-medium">Section Name</label>
                    <input type="text" name="section_name" class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium">Date & Time</label>
                    <input type="datetime-local" name="date_time" class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium">Image</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 w-full">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add Quiz</button>
                </div>
            </form>
        </div>

        {{-- Display Stored Sections --}}
        <div class="space-y-4 mt-6">
            @forelse($sections as $section)
                <div class="flex items-center p-4 border rounded-lg shadow-sm bg-white">
                    @if($section->image)
                        <img src="{{ asset('storage/' . $section->image) }}" alt="{{ $section->name }}" class="w-20 h-20 object-contain rounded-md mr-4">
                    @else
                        <img src="{{ asset('images/default_quiz.png') }}" alt="No Image" class="w-20 h-20 object-contain rounded-md mr-4">
                    @endif
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">{{ $section->name }}</h3>
                        <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($section->date_time)->format('d M Y, h:i A') }}</p>
                        <button class="mt-2 px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Add Questions</button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No sections available.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
