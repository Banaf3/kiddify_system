<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👨‍👩‍👧‍👦 {{ __('Parent Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-800">Welcome, Parent! 👋</h3>
                    <p class="text-gray-600 mt-2">Track your children's progress and manage your family account.</p>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-4">Your Children</h3>

            @if($children->isEmpty())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                You haven't added any children yet.
                                <a href="{{ route('parent.add-kid') }}"
                                    class="font-medium underline hover:text-yellow-600">Add your first child now</a>.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($children as $child)
                        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                                        {{ substr($child->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-bold text-gray-900">{{ $child->user->name }}</h4>
                                        <p class="text-gray-500 text-xs">{{ $child->user->email }}</p>
                                    </div>
                                </div>
                                <div class="border-t border-gray-100 py-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600 text-sm">Courses Enrolled:</span>
                                        <span class="font-semibold text-gray-800">{{ $child->enrolled_courses_count }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Avg. Grade:</span>
                                        <span
                                            class="font-semibold {{ $child->average_grade >= 70 ? 'text-green-600' : ($child->average_grade >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $child->average_grade }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Quick Actions --}}
            <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('parent.kids') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">👶 Manage Kids</h4>
                    <p class="text-gray-500 text-sm">View details and edit profiles.</p>
                </a>

                <a href="{{ route('parent.reports') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">📈 View Reports</h4>
                    <p class="text-gray-500 text-sm">Detailed progress reports for all children.</p>
                </a>

                <a href="{{ route('parent.add-kid') }}"
                    class="block bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-transparent hover:border-indigo-500">
                    <h4 class="font-bold text-gray-900 mb-1">➕ Add New Child</h4>
                    <p class="text-gray-500 text-sm">Register a new student account.</p>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>