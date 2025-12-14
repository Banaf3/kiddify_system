<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Select Your Role</h2>
        <p class="mt-2 text-sm text-gray-600">You have multiple accounts. Please choose which role you want to use:</p>
    </div>

    <form method="POST" action="{{ route('role.select.submit') }}">
        @csrf

        <div class="space-y-4">
            <!-- Teacher Option -->
            <div class="relative">
                <input type="radio" id="teacher" name="role" value="teacher" class="peer hidden" required>
                <label for="teacher"
                    class="flex items-center justify-between w-full p-4 text-gray-700 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        <div>
                            <span class="text-lg font-semibold">Teacher</span>
                            <p class="text-sm text-gray-500">Access teacher dashboard</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-blue-600 hidden peer-checked:block" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </label>
            </div>

            <!-- Parent Option -->
            <div class="relative">
                <input type="radio" id="parent" name="role" value="parent" class="peer hidden" required>
                <label for="parent"
                    class="flex items-center justify-between w-full p-4 text-gray-700 bg-white border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-green-600 peer-checked:bg-green-50 transition-all">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <div>
                            <span class="text-lg font-semibold">Parent</span>
                            <p class="text-sm text-gray-500">Access parent dashboard</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-green-600 hidden peer-checked:block" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </label>
            </div>
        </div>

        <x-input-error :messages="$errors->get('role')" class="mt-2" />

        <div class="mt-6">
            <button type="submit"
                class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Continue
            </button>
        </div>
    </form>

    <style>
        .peer:checked~label svg:last-child {
            display: block;
        }
    </style>
</x-guest-layout>
