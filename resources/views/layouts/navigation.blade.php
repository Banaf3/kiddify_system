<nav x-data="{ open: false }"
    style="background: linear-gradient(135deg, #FFF5F7 0%, #E8E0FF 100%); border-bottom: 2px solid rgba(236, 72, 153, 0.2); box-shadow: 0 4px 12px rgba(147, 112, 219, 0.1);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-16 w-auto"
                            style="filter: drop-shadow(0 2px 4px rgba(255, 105, 180, 0.2));" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="guest-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        {{ __('Dashboard') }}
                    </a>

                    @if (Auth::user()->isAdmin())
                        <!-- Admin Navigation -->
                        <a href="{{ route('admin.users') }}"
                            class="guest-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            {{ __('Users') }}
                        </a>
                        <a href="{{ route('admin.add-teacher') }}"
                            class="guest-nav-link {{ request()->routeIs('admin.add-teacher') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            {{ __('Add Teacher') }}
                        </a>
                       <a href="{{ route('admin.courses.index') }}"
    class="guest-nav-link {{ request()->routeIs('admin.courses.index') ? 'active' : '' }}">
    
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 20l9-5-9-5-9 5 9 5zm0-8l9-5-9-5-9 5 9 5z" />
    </svg>

    {{ __('Manage Courses') }}
</a>

                        

                        <a href="{{ route('admin.reports') }}"
                            class="guest-nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            {{ __('Reports') }}
                        </a>
                    @elseif(Auth::user()->isTeacher())
                        <!-- Teacher Navigation -->
                         <a href="{{ route('teacher.courses.index') }}"
    class="guest-nav-link {{ request()->routeIs('teacher.courses.index') ? 'active' : '' }}">

    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 6H4m16 6H4m16 6H4" />
    </svg>

    {{ __('My Courses') }}
</a>

                        <a href="{{ route('teacher.assessments') }}"
                            class="guest-nav-link {{ request()->routeIs('teacher.assessments') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            {{ __('Assessments') }}
                        </a>
                        <a href="{{ route('teacher.grading') }}"
                            class="guest-nav-link {{ request()->routeIs('teacher.grading') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            {{ __('Grading') }}
                        </a>
                    @elseif(Auth::user()->isStudent())
                        <!-- Student Navigation -->
                        <a href="{{ route('student.courses.index') }}"
    class="guest-nav-link {{ request()->routeIs('student.courses.index') ? 'active' : '' }}">

    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 7h18M3 12h18M3 17h18" />
    </svg>

    {{ __('My Courses') }}
</a>


                        <a href="{{ route('student.assessments') }}"
                            class="guest-nav-link {{ request()->routeIs('student.assessments') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            {{ __('Assessments') }}
                        </a>
                        <a href="{{ route('student.results') }}"
                            class="guest-nav-link {{ request()->routeIs('student.results') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            {{ __('My Results') }}
                        </a>
                    @elseif(Auth::user()->isParent())
                        <!-- Parent Navigation -->
                        <a href="{{ route('parent.kids') }}"
                            class="guest-nav-link {{ request()->routeIs('parent.kids') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            {{ __('My Kids') }}
                        </a>

                        <a href="{{ route('parent.reports') }}"
                            class="guest-nav-link {{ request()->routeIs('parent.reports') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            {{ __('Reports') }}
                        </a>
                        <a href="{{ route('parent.add-kid') }}"
                            class="guest-nav-link {{ request()->routeIs('parent.add-kid') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            {{ __('Add Kid') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-4 py-2 border-2 border-pink-300 text-sm leading-4 font-semibold rounded-xl bg-white hover:bg-pink-50 focus:outline-none transition ease-in-out duration-200"
                            style="color: #9333EA;">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl hover:bg-pink-50 focus:outline-none focus:bg-pink-50 transition duration-200 ease-in-out"
                    style="color: #9333EA;">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (Auth::user()->isAdmin())
                <!-- Admin Responsive Navigation -->
                <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
    {{ __('Courses') }}
</x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->isTeacher())
                <!-- Teacher Responsive Navigation -->
               <x-responsive-nav-link :href="route('teacher.courses.index')" :active="request()->routeIs('teacher.courses.*')">
    {{ __('My Courses') }}
</x-responsive-nav-link>

                <x-responsive-nav-link :href="route('teacher.assessments')" :active="request()->routeIs('teacher.assessments')">
                    {{ __('Assessments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('teacher.grading')" :active="request()->routeIs('teacher.grading')">
                    {{ __('Grading') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->isStudent())
                <!-- Student Responsive Navigation -->
                <x-responsive-nav-link :href="route('student.courses.index')" :active="request()->routeIs('student.courses.*')">
    {{ __('My Courses') }}
</x-responsive-nav-link>

                <x-responsive-nav-link :href="route('student.assessments')" :active="request()->routeIs('student.assessments')">
                    {{ __('Assessments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('student.results')" :active="request()->routeIs('student.results')">
                    {{ __('My Results') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->isParent())
                <!-- Parent Responsive Navigation -->
                <x-responsive-nav-link :href="route('parent.kids')" :active="request()->routeIs('parent.kids')">
                    {{ __('My Kids') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('parent.reports')" :active="request()->routeIs('parent.reports')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
