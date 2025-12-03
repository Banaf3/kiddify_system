<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    @include('layouts.guest-navigation')

    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4"
        style="background: linear-gradient(135deg, #FFF5F7 0%, #F0E8FF 100%);">
        <div class="w-full max-w-md px-8 py-8 bg-white shadow-2xl overflow-hidden"
            style="border-radius: 24px; box-shadow: 0 20px 60px rgba(147, 112, 219, 0.2);">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
