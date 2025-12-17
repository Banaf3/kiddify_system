<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-[#5A9CB5] leading-tight">👥 {{ $course->Title }}</h2>
            @if(!empty($course->description))
                <p class="text-sm text-gray-600 mt-1">{{ $course->description }}</p>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('teacher.courses.index') }}"
                class="inline-block mb-4 px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 no-underline transition-colors max-w-fit">Back</a>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#FACE68]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Student Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">
                                Email</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->user->email ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>