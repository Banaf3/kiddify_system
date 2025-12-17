<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-[#5A9CB5] leading-tight">
            👨‍👩‍👧‍👦 {{ __('Parent Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome --}}
            <div
                class="bg-gradient-to-r from-[#5A9CB5] to-[#4A8CA5] rounded-[32px] px-8 pb-8 pt-12 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-3xl font-extrabold mb-2">Welcome, Parent! 👋</h3>
                    <p class="text-blue-100 text-lg">Track your children's progress and manage your family account.</p>
                </div>
                <!-- Decorative Circle -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full"></div>
            </div>

            {{-- Quick Actions --}}
            <h3 class="text-2xl font-bold text-gray-800 mb-6 pl-2">Family Management</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('parent.kids') }}"
                    class="group bg-white rounded-[32px] p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-b-8 border-[#5A9CB5] no-underline">
                    <div class="text-center group-hover:scale-110 transition-transform duration-300">
                        <div class="text-4xl mb-4">👶</div>
                        <h4 class="text-xl font-bold text-gray-800 group-hover:text-[#5A9CB5] mb-2">Manage Kids</h4>
                        <p class="text-gray-500 group-hover:text-gray-700 text-sm font-medium">View & Edit Profiles</p>
                    </div>
                </a>

                <a href="{{ route('parent.reports') }}"
                    class="group bg-white rounded-[32px] p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-b-8 border-[#FAAC68] no-underline">
                    <div class="text-center group-hover:scale-110 transition-transform duration-300">
                        <div class="text-4xl mb-4">📈</div>
                        <h4 class="text-xl font-bold text-gray-800 group-hover:text-[#FAAC68] mb-2">View Reports</h4>
                        <p class="text-gray-500 group-hover:text-gray-700 text-sm font-medium">Full Progress Detail
                        </p>
                    </div>
                </a>

                <a href="{{ route('parent.add-kid') }}"
                    class="group bg-white rounded-[32px] p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-b-8 border-[#FA6868] no-underline">
                    <div class="text-center group-hover:scale-110 transition-transform duration-300">
                        <div class="text-4xl mb-4">➕</div>
                        <h4 class="text-xl font-bold text-gray-800 group-hover:text-[#FA6868] mb-2">Add New Child</h4>
                        <p class="text-gray-500 group-hover:text-gray-700 text-sm font-medium">Enroll a sibling</p>
                    </div>
                </a>
            </div>

            <h3 class="text-2xl font-bold text-gray-800 pl-2 mb-6">Your Children</h3>

            @if($children->isEmpty())
                <div class="bg-[#FACE68]/10 border-l-8 border-[#FACE68] rounded-[24px] p-6 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-[#FACE68] p-3 rounded-full text-white">
                            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-gray-800">No children linked yet</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                You haven't added any children.
                                <a href="{{ route('parent.add-kid') }}"
                                    class="font-bold text-[#FAAC68] hover:underline hover:text-[#FACE68]">Add your first
                                    child now</a>.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($children as $child)
                        <div
                            class="bg-white rounded-[32px] p-6 shadow-[0_10px_30px_rgba(90,156,181,0.1)] border-2 border-transparent">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-16 h-16 rounded-full bg-[#E0F2F1] flex items-center justify-center text-[#5A9CB5] font-bold text-2xl">
                                    {{ substr($child->user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-xl font-bold text-gray-900 leading-tight">{{ $child->user->name }}</h4>
                                    <p class="text-gray-500 text-sm">{{ $child->user->email }}</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600 font-medium text-sm">Courses Enrolled:</span>
                                    <span class="font-bold text-[#5A9CB5]">{{ $child->enrolled_courses_count }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-medium text-sm">Avg. Grade:</span>
                                    <span
                                        class="font-extrabold {{ $child->average_grade >= 70 ? 'text-green-500' : ($child->average_grade >= 50 ? 'text-[#FACE68]' : 'text-[#FA6868]') }}">
                                        {{ $child->average_grade }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>