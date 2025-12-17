<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-[#FAAC68]">Join the Fun!</h2>
        <p class="mt-2 text-sm text-gray-600">Create your account to get started.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Info message for adding additional role -->
        @if (old('email'))
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-2xl">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> If you already have an account with this email, enter your existing password
                    to add a new role (Teacher or Parent) to your account.
                </p>
            </div>
        @endif

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full rounded-xl focus:ring-[#FAAC68] focus:border-[#FAAC68]"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl focus:ring-[#FAAC68] focus:border-[#FAAC68]"
                type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input id="phone_number"
                class="block mt-1 w-full rounded-xl focus:ring-[#FAAC68] focus:border-[#FAAC68]" type="text"
                name="phone_number" :value="old('phone_number')" required />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender"
                class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:ring-[#FAAC68] focus:border-[#FAAC68]"
                required>
                <option value="">Select gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Date of Birth -->
        <div class="mt-4">
            <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
            <x-text-input id="date_of_birth"
                class="block mt-1 w-full rounded-xl focus:ring-[#FAAC68] focus:border-[#FAAC68]" type="date"
                name="date_of_birth" :value="old('date_of_birth')" required max="2000-12-31"
                style="color-scheme: light;" />
            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Address')" />
            <textarea id="address" name="address"
                class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:ring-[#FAAC68] focus:border-[#FAAC68]"
                required>{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full rounded-xl focus:ring-[#FAAC68] focus:border-[#FAAC68]"
                type="password" name="password" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation"
                class="block mt-1 w-full rounded-xl focus:ring-[#FAAC68] focus:border-[#FAAC68]" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role (Hidden - Default to Parent) -->
        <input type="hidden" name="role" value="parent">

        <div class="mt-6">
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-white bg-[#FAAC68] hover:bg-[#FAAC68]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FAAC68] transition-all duration-300 transform hover:-translate-y-0.5">
                {{ __('Register') }}
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Already registered?
                <a class="font-bold text-[#5A9CB5] hover:text-[#FAAC68] transition-colors duration-200 no-underline"
                    href="{{ route('login') }}">
                    {{ __('Log in here') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>