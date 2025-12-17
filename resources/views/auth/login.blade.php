<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-[#5A9CB5]">Welcome Back!</h2>
        <p class="mt-2 text-sm text-gray-600">Ready to learn something new today?</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl focus:ring-[#5A9CB5] focus:border-[#5A9CB5]"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full rounded-xl focus:ring-[#5A9CB5] focus:border-[#5A9CB5]"
                type="password" name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-[#5A9CB5] shadow-sm focus:ring-[#5A9CB5]" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-[#5A9CB5] hover:text-[#FAAC68] transition-colors duration-200 no-underline font-semibold"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-white bg-[#5A9CB5] hover:bg-[#5A9CB5]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5A9CB5] transition-all duration-300 transform hover:-translate-y-0.5">
                {{ __('Log in') }}
            </button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}"
                    class="font-bold text-[#FAAC68] hover:text-[#FA6868] transition-colors duration-200 no-underline">
                    {{ __('Register here') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>