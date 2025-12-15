<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Student Progress Reports
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Filter by Course</h3>
                    
                    <form method="GET" action="{{ route('admin.reports') }}">
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Course (Optional)
                                </label>
                                <select name="course_id" id="course_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Courses</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->CourseID }}" {{ $courseId == $course->CourseID ? 'selected' : '' }}>
                                            {{ $course->Title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Students Progress Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">All Students Progress</h3>

                    @if($studentsData->isEmpty())
                        <p class="text-gray-500 text-center py-4">No student data available.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Attempts</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessments Taken</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Score</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($studentsData as $data)
                                        <tr>
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $data['total_attempts'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $data['assessments_taken'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($data['assessments_taken'] > 0)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($data['average_percentage'] >= 70) bg-green-100 text-green-800
                                                        @elseif($data['average_percentage'] >= 50) bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ $data['average_percentage'] }}%
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 italic">No assessments</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Total Students</p>
                                <p class="text-2xl font-bold text-blue-600">{{ $studentsData->count() }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Active Students</p>
                                <p class="text-2xl font-bold text-green-600">{{ $studentsData->where('assessments_taken', '>', 0)->count() }}</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Total Attempts</p>
                                <p class="text-2xl font-bold text-purple-600">{{ $studentsData->sum('total_attempts') }}</p>
                            </div>
                            <div class="bg-orange-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Average Performance</p>
                                <p class="text-2xl font-bold text-orange-600">
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
