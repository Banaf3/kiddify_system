<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl leading-tight"
            style="background: linear-gradient(135deg, #EC4899 0%, #A855F7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            {{ __('Assessments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-3xl"
                style="border: 2px solid rgba(236, 72, 153, 0.1);">
                <div class="p-8">
                    <div>
                        <h3 class="text-2xl font-bold" style="color: #9333EA;">Assessments</h3>
                        <p class="text-gray-600 mt-1">Create and manage assessments for your courses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
