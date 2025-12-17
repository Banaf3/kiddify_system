<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kiddify - Fun Learning for Kids</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon_io/site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon_io/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen flex flex-col">
        @include('layouts.guest-navigation')

        <!-- Hero Section -->
        <main class="flex-grow">
            <!-- Hero -->
            <div class="relative overflow-hidden bg-white">
                <div class="max-w-7xl mx-auto">
                    <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                        <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2"
                            fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                            <polygon points="50,0 100,0 50,100 0,100" />
                        </svg>

                        <div class="pt-10 mx-auto max-w-7xl px-4 sm:pt-12 sm:px-6 md:pt-16 lg:pt-20 lg:px-8 xl:pt-28">
                            <div class="sm:text-center lg:text-left">
                                <h1
                                    class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                    <span class="block xl:inline text-kiddify-blue">Smart learning</span>
                                    <span class="block text-kiddify-orange xl:inline">for bright futures</span>
                                </h1>
                                <p
                                    class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                    The Kiddify Centralized School Management System makes preschool education easier,
                                    more fun, and organized for everyone. Connect teachers, parents, and little
                                    learners.
                                </p>
                                <div class="mt-5 sm:mt-8 flex justify-center lg:justify-start">
                                    <div class="rounded-full shadow-lg">
                                        <a href="{{ route('register') }}"
                                            class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-full text-white bg-kiddify-red hover:bg-red-500 md:py-4 md:text-lg md:px-10 transition-all duration-300 transform hover:-translate-y-1 no-underline">
                                            Get Started
                                        </a>
                                    </div>
                                    <div class="ml-8">
                                        <a href="{{ route('login') }}"
                                            class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-full text-kiddify-blue bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10 transition-all duration-300 no-underline">
                                            Log In
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                    <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full"
                        src="{{ asset('images/kids_learning_online.jpg') }}" alt="Children learning online">
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-12 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-base text-kiddify-blue font-semibold tracking-wide uppercase">Core Features</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Everything needed to manage a preschool
                        </p>
                    </div>

                    <div class="mt-10">
                        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">

                            <!-- User Module -->
                            <div
                                class="bg-white overflow-hidden shadow-lg rounded-2xl hover:shadow-2xl transition-shadow duration-300">
                                <div class="p-6">
                                    <div
                                        class="flex items-center justify-center h-12 w-12 rounded-md bg-kiddify-blue text-white mb-4">
                                        <!-- Heroicon users -->
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg leading-6 font-bold text-gray-900">User Management</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Secure login and profile management for admins, teachers, parents, and students.
                                    </p>
                                </div>
                                <div class="bg-kiddify-blue px-6 py-2">
                                </div>
                            </div>

                            <!-- Course Module -->
                            <div
                                class="bg-white overflow-hidden shadow-lg rounded-2xl hover:shadow-2xl transition-shadow duration-300">
                                <div class="p-6">
                                    <div
                                        class="flex items-center justify-center h-12 w-12 rounded-md bg-kiddify-yellow text-white mb-4">
                                        <!-- Heroicon book-open -->
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg leading-6 font-bold text-gray-900">Course Registration</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Easy enrollment and class organization for diverse learning paths.
                                    </p>
                                </div>
                                <div class="bg-kiddify-yellow px-6 py-2">
                                </div>
                            </div>

                            <!-- Assessment Module -->
                            <div
                                class="bg-white overflow-hidden shadow-lg rounded-2xl hover:shadow-2xl transition-shadow duration-300">
                                <div class="p-6">
                                    <div
                                        class="flex items-center justify-center h-12 w-12 rounded-md bg-kiddify-orange text-white mb-4">
                                        <!-- Heroicon pencil-alt -->
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg leading-6 font-bold text-gray-900">Interactive Assessments</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Engaging quizzes and tests designed to make learning rewarding.
                                    </p>
                                </div>
                                <div class="bg-kiddify-orange px-6 py-2">
                                </div>
                            </div>

                            <!-- Progress Module -->
                            <div
                                class="bg-white overflow-hidden shadow-lg rounded-2xl hover:shadow-2xl transition-shadow duration-300">
                                <div class="p-6">
                                    <div
                                        class="flex items-center justify-center h-12 w-12 rounded-md bg-kiddify-red text-white mb-4">
                                        <!-- Heroicon chart-bar -->
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg leading-6 font-bold text-gray-900">Progress Tracking</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Comprehensive reports for parents and teachers to monitor growth.
                                    </p>
                                </div>
                                <div class="bg-kiddify-red px-6 py-2">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
                <div class="flex justify-center space-x-6 md:order-2">
                </div>
                <div class="mt-8 md:mt-0 md:order-1">
                    <p class="text-center text-base text-gray-400">
                        &copy; {{ date('Y') }} Kiddify Systems. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>