<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl leading-tight"
            style="background: linear-gradient(135deg, #EC4899 0%, #A855F7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-3xl"
                style="border: 2px solid rgba(236, 72, 153, 0.1);">
                <div class="p-8">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Phone Number -->
                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number"
                                :value="old('phone_number', $user->phone_number)" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <!-- Gender -->
                        <div class="mt-4">
                            <x-input-label for="gender" :value="__('Gender')" />
                            <select id="gender" name="gender"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                    Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                                    Female</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <!-- Date of Birth -->
                        <div class="mt-4">
                            <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                            <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date"
                                name="date_of_birth" :value="old('date_of_birth', $user->date_of_birth)" required max="2000-12-31"
                                style="color-scheme: light;" />
                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>{{ old('address', $user->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                </option>
                                <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>
                                    Teacher</option>
                                <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>
                                    Student</option>
                                <option value="parent" {{ old('role', $user->role) == 'parent' ? 'selected' : '' }}>
                                    Parent</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Account Status (for students) -->
                        @if ($user->role == 'student')
                            <div class="mt-4">
                                <x-input-label for="account_status" :value="__('Account Status')" />
                                <select id="account_status" name="account_status"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                    onchange="togglePasswordField()">
                                    <option value="inactive"
                                        {{ old('account_status', $user->student->account_status ?? 'inactive') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                    <option value="active"
                                        {{ old('account_status', $user->student->account_status ?? 'inactive') == 'active' ? 'selected' : '' }}>
                                        Active</option>
                                </select>
                                <x-input-error :messages="$errors->get('account_status')" class="mt-2" />
                            </div>

                            <!-- Password field (shown when activating inactive student) -->
                            <div class="mt-4" id="password_field"
                                style="display: {{ old('account_status', $user->student->account_status ?? 'inactive') == 'inactive' ? 'block' : 'none' }};">
                                <x-input-label for="student_password" :value="__('Set Password (Required to activate)')" />
                                <x-text-input id="student_password" class="block mt-1 w-full" type="password"
                                    name="student_password" />
                                <x-input-error :messages="$errors->get('student_password')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">Enter a password to activate this student account
                                </p>
                            </div>

                            <script>
                                function togglePasswordField() {
                                    const status = document.getElementById('account_status').value;
                                    const passwordField = document.getElementById('password_field');
                                    const passwordInput = document.getElementById('student_password');

                                    if (status === 'inactive') {
                                        passwordField.style.display = 'block';
                                        passwordInput.removeAttribute('required');
                                    } else if (status === 'active') {
                                        const currentStatus = '{{ $user->student->account_status ?? 'inactive' }}';
                                        if (currentStatus === 'inactive') {
                                            passwordField.style.display = 'block';
                                            passwordInput.setAttribute('required', 'required');
                                        } else {
                                            passwordField.style.display = 'none';
                                            passwordInput.removeAttribute('required');
                                        }
                                    }
                                }

                                // Run on page load
                                document.addEventListener('DOMContentLoaded', function() {
                                    togglePasswordField();
                                });
                            </script>
                        @endif

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
                                style="background: linear-gradient(135deg, #EC4899 0%, #A855F7 100%); box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);">
                                {{ __('Update User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
