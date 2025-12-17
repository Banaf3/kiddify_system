<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-[#5A9CB5] leading-tight">
            📅 My Walking Schedule
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white overflow-hidden shadow-[0_10px_30px_rgba(90,156,181,0.1)] rounded-[32px] border border-gray-100">
                <div class="p-8">
                    @if($courses->isEmpty())
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">No Scheduled Classes Yet</h3>
                            <p class="text-gray-500">You don't have any classes assigned to your schedule at the moment.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-[#FACE68]">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Course Title</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Days</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Time</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Students</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($courses as $course)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-gray-900">{{ $course->Title }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex gap-1 flex-wrap">
                                                    @foreach(json_decode($course->days, true) ?? [] as $day)
                                                        <span
                                                            class="px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700">
                                                            {{ substr($day, 0, 3) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                                {{ \Carbon\Carbon::parse($course->Start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($course->end_time)->format('g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#5A9CB5]/10 text-[#5A9CB5]">
                                                    {{ $course->students->count() }} Students
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('teacher.courses.show', $course->CourseID) }}"
                                                    class="text-[#5A9CB5] hover:text-[#4A8CA5] font-bold hover:underline">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>