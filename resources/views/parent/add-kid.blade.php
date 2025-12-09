<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl leading-tight"
            style="background: linear-gradient(135deg, #EC4899 0%, #A855F7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            {{ __('Add Kid') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-3xl"
                style="border: 2px solid rgba(236, 72, 153, 0.1);">
                <div class="p-8">
                    <form method="POST" action="{{ route('parent.store-kid') }}">
                        @csrf

                        <!-- Kid's Name -->
                        <div>
                            <x-input-label for="kid_name" :value="__('Kid\'s Name')" />
                            <x-text-input id="kid_name" class="block mt-1 w-full" type="text" name="kid_name"
                                :value="old('kid_name')" required autofocus />
                            <x-input-error :messages="$errors->get('kid_name')" class="mt-2" />
                        </div>

                        <!-- Kid's Email -->
                        <div class="mt-4">
                            <x-input-label for="kid_email" :value="__('Kid\'s Email')" />
                            <x-text-input id="kid_email" class="block mt-1 w-full" type="email" name="kid_email"
                                :value="old('kid_email')" required />
                            <x-input-error :messages="$errors->get('kid_email')" class="mt-2" />
                        </div>

                        <!-- Kid's Phone Number -->
                        <div class="mt-4">
                            <x-input-label for="kid_phone" :value="__('Kid\'s Phone Number (Optional)')" />
                            <x-text-input id="kid_phone" class="block mt-1 w-full" type="text" name="kid_phone"
                                :value="old('kid_phone')" />
                            <x-input-error :messages="$errors->get('kid_phone')" class="mt-2" />
                        </div>

                        <!-- Kid's Gender -->
                        <div class="mt-4">
                            <x-input-label for="kid_gender" :value="__('Gender')" />
                            <select id="kid_gender" name="kid_gender"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Select gender</option>
                                <option value="male" {{ old('kid_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('kid_gender') == 'female' ? 'selected' : '' }}>Female
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('kid_gender')" class="mt-2" />
                        </div>

                        <!-- Kid's Date of Birth -->
                        <div class="mt-4">
                            <x-input-label for="kid_dob" :value="__('Date of Birth')" />
                            <x-text-input id="kid_dob" class="block mt-1 w-full" type="date" name="kid_dob"
                                :value="old('kid_dob')" required style="color-scheme: light;" />
                            <x-input-error :messages="$errors->get('kid_dob')" class="mt-2" />
                        </div>

                        <!-- Kid's Address -->
                        <div class="mt-4">
                            <x-input-label for="kid_address" :value="__('Address')" />
                            <textarea id="kid_address" name="kid_address" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>{{ old('kid_address') }}</textarea>
                            <x-input-error :messages="$errors->get('kid_address')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('parent.kids') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
                                style="background: linear-gradient(135deg, #EC4899 0%, #A855F7 100%); box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);">
                                {{ __('Add Kid') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
