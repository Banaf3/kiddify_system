<nav
    style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-bottom: 2px solid rgba(236, 72, 153, 0.2); box-shadow: 0 4px 12px rgba(147, 112, 219, 0.1);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}">
                    <x-application-logo class="block h-16 w-auto"
                        style="filter: drop-shadow(0 2px 4px rgba(255, 105, 180, 0.2));" />
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="guest-nav-link">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="guest-nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="guest-nav-link {{ request()->routeIs('register') ? 'active' : '' }}">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>
