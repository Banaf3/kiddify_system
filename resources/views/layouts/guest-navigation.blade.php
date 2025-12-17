<nav class="bg-white/80 backdrop-blur-md border-b-4 border-kiddify-blue sticky top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="shrink-0 flex items-center transform hover:scale-105 transition-transform duration-300 -ml-8">
                <a href="{{ route('home') }}">
                    <x-application-logo class="block h-16 w-auto" />
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="font-bold text-lg text-kiddify-blue hover:text-kiddify-orange transition-colors duration-300">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-6 py-2 rounded-full font-bold text-white bg-kiddify-blue hover:bg-kiddify-blue/90 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 no-underline">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-6 py-2 rounded-full font-bold text-white bg-kiddify-orange hover:bg-kiddify-orange/90 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 no-underline">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>