<section class="bg-white p-8 rounded-lg shadow">
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            Profile
        </h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        @if (session('status') === 'profile-updated')
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-4">
                Profile updated successfully
            </div>
        @endif

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                    autofocus autocomplete="name">
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <!-- Email address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                    autocomplete="username">
                <x-input-error class="mt-1" :messages="$errors->get('email')" />
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone number</label>
                <input id="phone_number" name="phone_number" type="text"
                    value="{{ old('phone_number', $user->phone_number) }}" required>
                <x-input-error class="mt-1" :messages="$errors->get('phone_number')" />
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="" disabled {{ old('gender', $user->gender) ? '' : 'selected' }}>Select gender
                    </option>
                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female
                    </option>
                </select>
                <x-input-error class="mt-1" :messages="$errors->get('gender')" />
            </div>

            <!-- Date of Birth -->
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of birth</label>
                <input id="date_of_birth" name="date_of_birth" type="date" max="2000-12-31"
                    value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                    required>
                <x-input-error class="mt-1" :messages="$errors->get('date_of_birth')" />
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input id="address" name="address" type="text" value="{{ old('address', $user->address) }}"
                    required>
                <x-input-error class="mt-1" :messages="$errors->get('address')" />
            </div>
        </div>


        <div class="flex justify-end pt-4">
            <button type="submit" class="!w-auto px-10">
                Update Profile
            </button>
        </div>
    </form>
</section>
