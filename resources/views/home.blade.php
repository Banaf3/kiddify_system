<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kiddify - Home</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen" style="background: linear-gradient(135deg, #FFF5F7 0%, #F0E8FF 100%);">
        @include('layouts.guest-navigation')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-4" style="color: #EC4899;">Kiddify Preschool Learning System</h1>
                <p class="text-xl text-gray-700">Centralized School Management System</p>
            </div>



            <!-- Project Overview -->
            <div class="mb-8">
                <h3 class="text-xl font-bold mb-4" style="color: #9333EA;">About Kiddify LMS</h3>
                <p class="text-gray-700 mb-4">
                    The
                    K iddify Centralized School Management System is designed to address major challenges in the
                    preschool environment,

                    including user registration processes, course management, student assessment, and academic
                    performance monitoring.
                </p>
                <p class="text-gray-700">

                    This system combines four critical modules to enable seamless digital operations, improved data
                    accuracy,
                    and effective communication between teachers, parents, and administrators.
                </p>
            </div>

            <!-- Modules -->
            <div>
                <h3 class="text-xl font-bold mb-4" style="color: #EC4899;">System Modules</h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="p-6 rounded-xl"
                        style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);">

                        <h4 class="font-bold text-lg mb-2" style="color: #EC4899;">Manage User Login and Profile</h4>

                        <p class="text-gray-700 text-sm">Complete CRUD operations for user management, authentication,
                            and prof
                            i le administration.</p>
                    </div>
                    <div class="p-6 rounded-xl"
                        style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);">
                        <h4 class="font-bold text-lg mb-2" style="color: #9333EA;">Manage Course Registration</h4>

                        <p class="text-gray-700 text-sm">Creating and organizing preschool courses with rol
                            e -based access control.</p>
                    </div>
                    <div class="p-6 rounded-xl"
                        style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%);">
                        <h4 class="font-bold text-lg mb-2" style="color: #EC4899;">Manage Student Assessment</h4>

                        <p class="text-gray-700 text-sm">Structured assessments based on course object
                            i ves and learning outcomes.</p>
                    </div>
                    <div class="p-6 rounded-xl"
                        style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);">
                        <h4 class="font-bold text-lg mb-2" style="color: #9333EA;">Manage Progress</ h 4>
                            <p class="text-gray-700 text-sm">Monitor academic development and provide performance
                                reports to students and parents.</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Call to Action -->
        <div class="text-center pb-12">
            <a href="{{ route('login') }}" class="btn-primary inline-block">
                GET STARTED
            </a>
        </div>
    </div>
    </div>
</body>

</html>
