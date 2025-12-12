<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile Verification') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 text-center">Verify Your Identity</h3>
                    <p class="text-sm text-gray-600 mb-6 text-center">Please enter your credentials to access your
                        profile</p>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.verify') }}" class="space-y-4">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                address</label>
                            <input id="email" type="email" name="email"
                                class="w-full px-3 py-2 bg-gray-100 border-0 rounded text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('email', auth()->user()->email) }}" required autofocus>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input id="password" type="password" name="password"
                                class="w-full px-3 py-2 bg-gray-100 border-0 rounded text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded transition">
                                Verify & Continue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
