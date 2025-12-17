<section>
    <header class="mb-6">
        <h2 class="text-2xl font-bold text-[#5A9CB5]">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4 text-center font-bold">
                {{ __('Profile updated successfully!') }}
            </div>
        @endif

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                    required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                    required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Phone Number -->
            <div>
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full"
                    :value="old('phone_number', $user->phone_number)" required />
                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
            </div>

            <!-- Gender -->
            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <select id="gender" name="gender"
                    class="mt-1 block w-full border-gray-300 focus:border-[#5A9CB5] focus:ring-[#5A9CB5] rounded-xl shadow-sm text-gray-700 h-[46px]"
                    required>
                    <option value="" disabled {{ old('gender', $user->gender) ? '' : 'selected' }}>Select gender
                    </option>
                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female
                    </option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            <!-- Date of Birth -->
            <div>
                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full"
                    max="2000-12-31" :value="old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
            </div>

            <!-- Address -->
            <div>
                <x-input-label for="address" :value="__('Address')" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)"
                    required />
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-[#5A9CB5] border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#4A8CA5] active:bg-[#4A8CA5] focus:outline-none focus:ring-2 focus:ring-[#5A9CB5] focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Save') }}
            </button>
        </div>
    </form>
</section>
