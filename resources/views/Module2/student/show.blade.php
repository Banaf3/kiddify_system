<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📅 My Timetable</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('student.courses.index') }}" class="inline-block mb-4 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">⬅️ Back to Courses</a>

            <div class="bg-white shadow-md rounded-lg p-4 overflow-x-auto">

                {{-- Week Days Header --}}
                <div class="grid grid-cols-5 gap-2 mb-2 text-center font-semibold bg-yellow-100 p-2 rounded">
                    <div>Monday</div>
                    <div>Tuesday</div>
                    <div>Wednesday</div>
                    <div>Thursday</div>
                    <div>Friday</div>
                </div>

                {{-- Time Slots Grid --}}
                <div class="relative h-[720px] border-l border-t border-gray-200">

                    @php
                        // Time slots: 7am - 7pm (12 hours)
                        $startHour = 7;
                        $endHour = 19;
                        $hoursInMinutes = ($endHour - $startHour) * 60;
                        $dayColumns = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
                    @endphp

                    @foreach($dayColumns as $colIndex => $day)
                        <div class="absolute top-0 left-{{ $colIndex * 20 }}% w-1/5 h-full border-r border-gray-200">
                            @foreach($courses as $course)
                                @if(in_array($day, json_decode($course->days, true)))
                                    @php
                                        $courseStart = strtotime($course->Start_time);
                                        $courseEnd = strtotime($course->end_time);
                                        $totalMinutes = ($endHour - $startHour) * 60;
                                        $topPercent = ((date('H', $courseStart) - $startHour) * 60 + date('i', $courseStart)) / $totalMinutes * 100;
                                        $heightPercent = ((date('H', $courseEnd) - date('H', $courseStart)) * 60 + (date('i', $courseEnd) - date('i', $courseStart))) / $totalMinutes * 100;
                                    @endphp
                                    <div class="absolute left-1/10 w-4/5 bg-blue-500 text-white rounded p-1 text-xs overflow-hidden"
                                         style="top: {{ $topPercent }}%; height: {{ $heightPercent }}%;">
                                        <div class="font-bold">{{ $course->Title }}</div>
                                        <div class="text-[10px]">{{ $course->teacher->user->name ?? 'N/A' }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                    {{-- Hour Lines --}}
                    @for($h=$startHour; $h<=$endHour; $h++)
                        @php $topPercent = (($h - $startHour) * 60) / $hoursInMinutes * 100; @endphp
                        <div class="absolute left-0 w-full border-t border-gray-300 text-xs text-gray-500"
                             style="top: {{ $topPercent }}%;">
                            {{ sprintf("%02d:00", $h) }}
                        </div>
                    @endfor

                </div>

            </div>
        </div>
    </div>
</x-app-layout>




