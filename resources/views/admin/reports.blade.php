<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-[#5A9CB5] leading-tight">
            📊 Student Progress Reports
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div
                class="bg-white overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.05)] rounded-[20px] mb-6 border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Filter by Course</h3>

                    <form method="GET" action="{{ route('admin.reports') }}">
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <label for="course_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Select Course (Optional)
                                </label>
                                <select name="course_id" id="course_id"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-[#5A9CB5] focus:ring-[#5A9CB5]">
                                    <option value="">All Courses</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->CourseID }}" {{ $courseId == $course->CourseID ? 'selected' : '' }}>
                                            {{ $course->Title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit"
                                    class="px-6 py-2.5 bg-[#5A9CB5] text-white font-bold rounded-xl hover:bg-[#4A8CA5] shadow-md transition-all">
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Students Progress Table -->
            <div
                class="bg-white overflow-hidden shadow-[0_10px_30px_rgba(90,156,181,0.1)] rounded-[32px] border border-gray-100">
                <div class="p-8 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">All Students Progress</h3>

                    @if($studentsData->isEmpty())
                        <p class="text-gray-500 text-center py-4">No student data available.</p>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-100 mb-8">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-[#FACE68]">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Student Name</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Email</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Total Attempts</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Assessments Taken</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            Average Score</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($studentsData as $data)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $data['student']->user->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">
                                                    {{ $data['student']->user->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                                {{ $data['total_attempts'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                                {{ $data['assessments_taken'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($data['assessments_taken'] > 0)
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                                    @if($data['average_percentage'] >= 70) bg-green-100 text-green-800
                                                                    @elseif($data['average_percentage'] >= 50) bg-[#FACE68]/20 text-yellow-800
                                                                    @else bg-red-100 text-red-800
                                                                    @endif">
                                                        {{ $data['average_percentage'] }}%
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic text-sm">No assessments</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-[#5A9CB5]/10 p-4 rounded-2xl border border-[#5A9CB5]/20">
                                <p class="text-sm font-bold text-gray-600 uppercase tracking-wide">Total Students</p>
                                <p class="text-3xl font-extrabold text-[#5A9CB5] mt-1">{{ $studentsData->count() }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-2xl border border-green-100">
                                <p class="text-sm font-bold text-gray-600 uppercase tracking-wide">Active Students</p>
                                <p class="text-3xl font-extrabold text-green-600 mt-1">
                                    {{ $studentsData->where('assessments_taken', '>', 0)->count() }}</p>
                            </div>
                            <div class="bg-[#FACE68]/10 p-4 rounded-2xl border border-[#FACE68]/20">
                                <p class="text-sm font-bold text-gray-600 uppercase tracking-wide">Total Attempts</p>
                                <p class="text-3xl font-extrabold text-[#D4A328] mt-1">
                                    {{ $studentsData->sum('total_attempts') }}</p>
                            </div>
                            <div class="bg-[#FAAC68]/10 p-4 rounded-2xl border border-[#FAAC68]/20">
                                <p class="text-sm font-bold text-gray-600 uppercase tracking-wide">Average Perf.</p>
                                <p class="text-3xl font-extrabold text-[#E58A3C] mt-1">
                                    {{ $studentsData->where('assessments_taken', '>', 0)->avg('average_percentage') ? round($studentsData->where('assessments_taken', '>', 0)->avg('average_percentage'), 1) : 0 }}%
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>