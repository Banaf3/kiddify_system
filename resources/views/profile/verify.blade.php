<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Verify Your Identity</h2>
        <p class="mt-2 text-sm text-gray-600">
            Please enter your credentials to access your profile
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.verify') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', auth()->user()->email)"
                required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit"
                class="w-full px-4 py-3 text-white bg-kiddify-blue hover:bg-kiddify-blue/90 rounded-lg focus:outline-none focus:ring-2 focus:ring-kiddify-blue focus:ring-offset-2 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                Verify & Continue
            </button>
        </div>
    </form>

    <!-- Cancel -->
    <div class="mt-4 text-center">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-800 underline">
            Cancel
        </a>
    </div>
</x-guest-layout>
