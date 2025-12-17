<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-[#5A9CB5] leading-tight">
            🛡️ {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome --}}
            <div
                class="bg-gradient-to-r from-[#5A9CB5] to-[#4A8CA5] rounded-[32px] px-8 pb-8 pt-12 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-3xl font-extrabold mb-2">Hello, Administrator! 🛡️</h3>
                    <p class="text-blue-100 text-lg">System Status & Management.</p>
                </div>
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full"></div>
            </div>

            {{-- Quick Actions --}}
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-6 pl-2">System Management</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('admin.users') }}"
                        class="group bg-white rounded-[32px] p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-b-8 border-[#5A9CB5] no-underline">
                        <div class="text-center group-hover:scale-110 transition-transform duration-300">
                            <div class="text-5xl mb-4">👥</div>
                            <h4 class="text-xl font-bold text-gray-800 group-hover:text-[#5A9CB5] mb-2">Manage Users</h4>
                            <p class="text-gray-500 group-hover:text-gray-700 text-sm font-medium">Students, Teachers & Parents
                            </p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports') }}"
                        class="group bg-white rounded-[32px] p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-b-8 border-[#FACE68] no-underline">
                        <div class="text-center group-hover:scale-110 transition-transform duration-300">
                            <div class="text-5xl mb-4">📊</div>
                            <h4 class="text-xl font-bold text-gray-800 group-hover:text-[#F0A058] mb-2">System Reports</h4>
                            <p class="text-gray-500 group-hover:text-gray-700 text-sm font-medium">Analytics & Insights
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- Total Users --}}
                <div
                    class="bg-white rounded-[32px] p-6 shadow-[0_10px_30px_rgba(90,156,181,0.1)] border-2 border-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 rounded-2xl bg-[#5A9CB5]/10 text-[#5A9CB5]">
                            <span class="text-3xl">👥</span>
                        </div>
                        <span class="text-[#5A9CB5] font-bold text-4xl">{{ $totalUsers }}</span>
                    </div>
                    <h4 class="text-gray-800 font-bold text-lg">Total Users</h4>
                    <p class="text-gray-500 text-sm">Active accounts</p>
                </div>

                {{-- Active Courses --}}
                <div
                    class="bg-white rounded-[32px] p-6 shadow-[0_10px_30px_rgba(90,156,181,0.1)] border-2 border-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 rounded-2xl bg-[#FACE68]/10 text-[#FACE68]">
                            <span class="text-3xl">📚</span>
                        </div>
                        <span class="text-[#FACE68] font-bold text-4xl">{{ $totalCourses }}</span>
                    </div>
                    <h4 class="text-gray-800 font-bold text-lg">Active Courses</h4>
                    <p class="text-gray-500 text-sm">Classes running</p>
                </div>

                {{-- Pending Students --}}
                <div
                    class="bg-white rounded-[32px] p-6 shadow-[0_10px_30px_rgba(90,156,181,0.1)] border-2 border-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 rounded-2xl bg-[#FAAC68]/10 text-[#FAAC68]">
                            <span class="text-3xl">🎓</span>
                        </div>
                        <span class="text-[#FAAC68] font-bold text-4xl">{{ $pendingStudents }}</span>
                    </div>
                    <h4 class="text-gray-800 font-bold text-lg">Pending Students</h4>
                    <p class="text-gray-500 text-sm">Waiting for approval</p>
                </div>

                {{-- Pending Teachers --}}
                <div
                    class="bg-white rounded-[32px] p-6 shadow-[0_10px_30px_rgba(90,156,181,0.1)] border-2 border-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-4 rounded-2xl bg-[#FA6868]/10 text-[#FA6868]">
                            <span class="text-3xl">👩‍🏫</span>
                        </div>
                        <span class="text-[#FA6868] font-bold text-4xl">{{ $pendingTeachers }}</span>
                    </div>
                    <h4 class="text-gray-800 font-bold text-lg">Pending Teachers</h4>
                    <p class="text-gray-500 text-sm">Waiting for approval</p>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white shadow-[0_10px_30px_rgba(0,0,0,0.05)] rounded-[32px] p-8 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="bg-[#5A9CB5]/10 text-[#5A9CB5] p-2 rounded-xl mr-3">🕒</span> Recent Registrations
                </h3>
                <ul class="space-y-4">
                    @forelse($recentUsers as $user)
                        <li class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition-colors border border-transparent hover:border-gray-100">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 rounded-full bg-[#E0F2F1] text-[#5A9CB5] flex items-center justify-center font-bold text-lg mr-4 shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-lg">{{ $user->name }}</p>
                                    <p class="text-gray-500 text-sm font-medium">{{ $user->email }} • <span
                                            class="capitalize bg-gray-100 px-2 py-0.5 rounded text-gray-600 text-xs">{{ $user->role }}</span></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </li>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-400 font-medium">No recent activity.</p>
                        </div>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>