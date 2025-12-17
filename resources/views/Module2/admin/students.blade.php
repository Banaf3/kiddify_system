<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-[#5A9CB5] leading-tight">👥 Students in {{ $course->Title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.courses.index') }}"
                class="inline-flex items-center mb-6 text-[#5A9CB5] hover:underline font-bold transition-all">
                ← Back to Courses
            </a>

            <div
                class="bg-white shadow-[0_10px_30px_rgba(90,156,181,0.1)] rounded-[32px] overflow-hidden border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#FACE68]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Student Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Email</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ $student->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $student->user->email ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>