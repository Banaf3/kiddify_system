<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-[#5A9CB5] leading-tight">📅 {{ __('My Timetable') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8">
                <a href="{{ route('student.courses.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-white text-gray-600 font-bold rounded-full shadow-md hover:shadow-lg hover:text-[#5A9CB5] transition-all duration-300 no-underline">
                    ← Back to Courses
                </a>

                <a href="{{ route('student.timetable.download') }}" target="_blank"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#FACE68] to-[#FAAC68] hover:from-[#FAAC68] hover:to-[#FA6868] text-white font-bold rounded-full shadow-lg transform hover:-translate-y-1 transition-all duration-300 no-underline">
                    Download PDF 📥
                </a>
            </div>

            @php
                $startHour = 7;
                $endHour = 19;
                $dayColumns = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

                // Helper function to convert 12-hour format to 24-hour integer
                function hour24($time)
                {
                    try {
                        $dt = \Carbon\Carbon::parse($time); // Auto detects AM/PM
                        return intval($dt->format('H'));
                    } catch (\Exception $e) {
                        return null;
                    }
                }
            @endphp

            <div class="bg-white shadow-[0_10px_30px_rgba(90,156,181,0.1)] rounded-[32px] p-8 overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="grid min-w-[800px]"
                        style="grid-template-columns: 80px repeat({{ count($dayColumns) }}, 1fr); gap: 1px; background-color: #f3f4f6; border-radius: 20px; text-align: center; overflow: hidden;">

                        {{-- Header Row --}}
                        <div class="bg-white"></div> {{-- Empty corner --}}
                        @foreach ($dayColumns as $day)
                            <div class="font-extrabold text-[#5A9CB5] bg-[#E0F2F1] p-4">
                                {{ $day }}
                            </div>
                        @endforeach

                        {{-- Time rows --}}
                        @for ($h = $startHour; $h < $endHour; $h++)
                            @php
                                $next = $h + 1;
                                $timeLabel = sprintf('%02d:00', $h);
                            @endphp

                            {{-- Time label column --}}
                            <div
                                class="p-4 text-xs font-bold text-gray-400 bg-gray-50 flex items-start justify-center pt-2">
                                {{ $timeLabel }}
                            </div>

                            {{-- Day columns --}}
                            @foreach ($dayColumns as $day)
                                <div
                                    class="relative bg-white h-24 border-l border-t border-gray-50 hover:bg-gray-50 transition-colors">

                                    @php
                                        // Find course in this slot, comparing 24-hour hours
                                        $class = $courses->first(function ($c) use ($day, $h) {
                                            $days = json_decode($c->days, true);
                                            $classHour = hour24($c->Start_time);
                                            return in_array($day, $days) && $classHour === $h;
                                        });

                                        // Random accent color for the block
                                        $colors = ['bg-[#5A9CB5]', 'bg-[#FACE68]', 'bg-[#FAAC68]', 'bg-[#FA6868]'];
                                        $randomColor = $colors[rand(0, 3)];
                                        // Note: rand() here causes jitter on refresh, ideally use consistent hashing if needed, but for kids UI variety is okay.
                                        // Improving consistency: simple hash based on title length
                                        if ($class) {
                                            $hash = strlen($class->Title) % 4;
                                            $randomColor = $colors[$hash];
                                        }
                                    @endphp

                                    @if($class)
                                        <div
                                            class="absolute inset-1 {{ $randomColor }} text-white text-xs rounded-xl p-2 shadow-md flex flex-col justify-center items-center text-center transform hover:scale-105 transition-transform z-10 cursor-default">
                                            <div class="font-bold text-sm leading-tight mb-1">{{ $class->Title }}</div>
                                            <div class="text-[10px] opacity-90 font-medium">
                                                {{ $class->teacher->user->name ?? 'Teacher' }}
                                            </div>
                                            <div class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full mt-1">
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
    </div>
</x-app-layout>