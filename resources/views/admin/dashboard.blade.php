<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🛡️ {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-800">Hello, Administrator! 🚀</h3>
                    <p class="text-gray-600 mt-2">Overview of the entire learning system.</p>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                {{-- Total Users --}}
                <div class="bg-blue-50 p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <p class="text-blue-600 font-bold uppercase text-xs">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                </div>

                {{-- Total Courses --}}
                <div class="bg-indigo-50 p-6 rounded-lg shadow border-l-4 border-indigo-500">
                    <p class="text-indigo-600 font-bold uppercase text-xs">Active Courses</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalCourses }}</p>
                </div>

                {{-- Pending Students --}}
                <div class="bg-yellow-50 p-6 rounded-lg shadow border-l-4 border-yellow-500">
                    <p class="text-yellow-600 font-bold uppercase text-xs">Pending Students</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingStudents }}</p>
                </div>

                {{-- Pending Teachers --}}
                <div class="bg-orange-50 p-6 rounded-lg shadow border-l-4 border-orange-500">
                    <p class="text-orange-600 font-bold uppercase text-xs">Pending Teachers</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingTeachers }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Quick Actions --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Quick Management</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.users') }}"
                            class="block p-4 bg-gray-50 hover:bg-gray-100 rounded border text-center transition">
                            <span class="text-2xl">👥</span>
                            <span class="block font-semibold mt-1">Manage Users</span>
                        </a>
                        <a href="{{ route('admin.reports') }}"
                            class="block p-4 bg-gray-50 hover:bg-gray-100 rounded border text-center transition">
                            <span class="text-2xl">📊</span>
                            <span class="block font-semibold mt-1">System Reports</span>
                        </a>
                        <!-- Add more admin links as needed (e.g. Manage Courses, Settings) -->
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Recent Registrations</h3>
                    <ul class="space-y-3">
                        @forelse($recentUsers as $user)
                            <li class="flex items-center justify-between text-sm">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600 mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-gray-500 text-xs">{{ $user->email }} • {{ ucfirst($user->role) }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                            </li>
                        @empty
                            <p class="text-gray-500 text-sm">No recent activity.</p>
                        @endforelse
                    </ul>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>