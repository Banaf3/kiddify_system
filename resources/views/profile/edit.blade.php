<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl leading-tight text-[#5A9CB5]">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Profile Info -->
            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-[32px] border border-gray-100">
                <div class="w-full max-w-none">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Update -->
            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-[32px] border border-gray-100">
                <div class="w-full max-w-none">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-4 sm:p-8 bg-white shadow-xl sm:rounded-[32px] border border-gray-100">
                <div class="w-full max-w-none">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>