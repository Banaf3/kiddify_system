<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📅 My Timetable</h2>
    </x-slot>

    

    <div class="py-6">

   <div class="flex justify-between items-center mb-4">
    <a href="{{ route('student.courses.index') }}"
       class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
        Back to Courses
    </a>

    <a href="{{ route('student.timetable.download') }}" target="_blank"
       class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
        Download Timetable (PDF)
    </a>
</div>




            @php
                $startHour = 7;
                $endHour = 19;
                $dayColumns = ['Monday','Tuesday','Wednesday','Thursday','Friday'];

                // Helper function to convert 12-hour format to 24-hour integer
                function hour24($time) {
                    try {
                        $dt = \Carbon\Carbon::parse($time); // Auto detects AM/PM
                        return intval($dt->format('H'));
                    } catch (\Exception $e) {
                        return null;
                    }
                }
            @endphp

            <div class="bg-white shadow-md rounded p-4 overflow-x-auto">

                <div class="grid"
                     style="grid-template-columns: 80px repeat({{ count($dayColumns) }}, 1fr);">

                    {{-- Header Row --}}
                    <div></div>
                    @foreach ($dayColumns as $day)
                        <div class="text-center font-semibold bg-yellow-100 p-2 border">
                            {{ $day }}
                        </div>
                    @endforeach

                    {{-- Time rows --}}
                    @for ($h = $startHour; $h < $endHour; $h++)
                        @php
                            $next = $h + 1;
                            $timeLabel = sprintf('%02d:00 - %02d:00', $h, $next);
                        @endphp

                        {{-- Time label column --}}
                        <div class="p-2 text-xs text-right border">
                            {{ $timeLabel }}
                        </div>

                        {{-- Day columns --}}
                        @foreach ($dayColumns as $day)
                            <div class="relative border h-20">

                                @php
                                    // Find course in this slot, comparing 24-hour hours
                                    $class = $courses->first(function($c) use($day, $h) {
                                        $days = json_decode($c->days, true);
                                        $classHour = hour24($c->Start_time);
                                        return in_array($day, $days) && $classHour === $h;
                                    });
                                @endphp

                                @if($class)
                                    <div class="absolute inset-1 bg-blue-500 text-white text-xs rounded p-1 shadow">
                                        <div class="font-bold">{{ $class->Title }}</div>
                                        <div class="text-[10px]">
                                            {{ $class->teacher->user->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-[10px]">
                                            {{ $class->Start_time }} - {{ $class->end_time }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                    @endfor

                </div>

            </div>

        </div>
    </div>
</x-app-layout>


