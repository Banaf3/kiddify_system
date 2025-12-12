<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl leading-tight"
            style="background: linear-gradient(135deg, #EC4899 0%, #A855F7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            {{ __('Add Teacher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-2xl rounded-3xl"
                style="border: 2px solid rgba(236, 72, 153, 0.1);">
                <div class="p-8">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold" style="color: #9333EA;">Add New Teacher</h3>
                        <p class="text-gray-600 mt-1">Fill in the information to create a new teacher account</p>
                    </div>

                    <form method="POST" action="{{ route('admin.add-teacher.store') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Phone Number -->
                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number"
                                :value="old('phone_number')" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <!-- Gender -->
                        <div class="mt-4">
                            <x-input-label for="gender" :value="__('Gender')" />
                            <select id="gender" name="gender"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <!-- Date of Birth -->
                        <div class="mt-4">
                            <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                            <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date"
                                name="date_of_birth" :value="old('date_of_birth')" required max="2000-12-31"
                                style="color-scheme: light;" />
                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Qualification -->
                        <div class="mt-4">
                            <x-input-label for="qualification" :value="__('Qualification')" />
                            <x-text-input id="qualification" class="block mt-1 w-full" type="text"
                                name="qualification" :value="old('qualification')" required />
                            <x-input-error :messages="$errors->get('qualification')" class="mt-2" />
                        </div>

                        <!-- Experience Years -->
                        <div class="mt-4">
                            <x-input-label for="experience_years" :value="__('Experience Years')" />
                            <x-text-input id="experience_years" class="block mt-1 w-full" type="number"
                                name="experience_years" :value="old('experience_years')" required min="0"
                                style="background-color: white; color: #1f2937;" />
                            <x-input-error :messages="$errors->get('experience_years')" class="mt-2" />
                        </div>

                        <!-- School Branch -->
                        <div class="mt-4">
                            <x-input-label for="school_branch" :value="__('School Branch')" />
                            <x-text-input id="school_branch" class="block mt-1 w-full" type="text"
                                name="school_branch" :value="old('school_branch')" required />
                            <x-input-error :messages="$errors->get('school_branch')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.users') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
                                style="background: linear-gradient(135deg, #EC4899 0%, #A855F7 100%); box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);">
                                {{ __('Add Teacher') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
