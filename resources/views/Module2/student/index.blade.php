<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-[#5A9CB5] leading-tight">📚 {{ __('My Courses') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($courses->isEmpty())
                <div class="bg-[#FACE68]/10 border-l-8 border-[#FACE68] rounded-[24px] p-8 text-center shadow-lg">
                    <div class="flex flex-col items-center">
                        <div class="text-4xl mb-4">😿</div>
                        <h3 class="text-xl font-bold text-gray-800">No courses yet!</h3>
                        <p class="text-gray-600 mt-2">You are not enrolled in any courses right now.</p>
                    </div>
                </div>
            @else
                {{-- View Timetable Button --}}
                <div class="mb-8 text-right">
                    <a href="{{ route('student.courses.timetable') }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#FACE68] to-[#FAAC68] hover:from-[#FAAC68] hover:to-[#FA6868] text-white font-bold rounded-full shadow-lg transform hover:-translate-y-1 transition-all duration-300 no-underline">
                        <span class="mr-2">📅</span> View Timetable
                    </a>
                </div>

                {{-- Courses Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($courses as $index => $course)
                        @php
                            // Cycle through brand colors for card borders
                            $colors = ['#5A9CB5', '#FACE68', '#FAAC68', '#FA6868'];
                            $color = $colors[$index % 4];
                        @endphp
                        <a href="{{ route('student.courses.sections', $course->CourseID) }}"
                            class="block group h-full no-underline">
                            <div class="bg-white rounded-[32px] overflow-hidden shadow-[0_10px_30px_rgba(90,156,181,0.1)] hover:shadow-2xl transition-all duration-300 border-b-8 h-full flex flex-col"
                                style="border-color: {{ $color }};">
                                <div class="p-8 flex-1">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-bold text-xl"
                                            style="background-color: {{ $color }};">
                                            {{ substr($course->Title, 0, 1) }}
                                        </div>
                                        <span
                                            class="bg-gray-100 text-gray-500 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Course</span>
                                    </div>
                                    <h3
                                        class="font-bold text-2xl text-gray-800 group-hover:text-[{{ $color }}] transition-colors mb-3 leading-tight">
                                        {{ $course->Title }}</h3>
                                    <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3">
                                        {{ $course->description ?? 'Ready to start learning? click here to begin!' }}</p>

                                    <div class="flex items-center text-sm text-gray-400 font-medium">
                                        <span class="mr-2">👨‍🏫</span>
                                        {{ $course->teacher->user->name ?? 'Teacher' }}
                                    </div>
                                </div>
                                <div
                                    class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center group-hover:bg-[{{ $color }}] group-hover:bg-opacity-10 transition-colors">
                                    <span class="font-bold text-sm" style="color: {{ $color }}">Start Learning</span>
                                    <span class="text-lg transform group-hover:translate-x-2 transition-transform">→</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>