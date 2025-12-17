<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon_io/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon_io/site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon_io/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    @include('layouts.guest-navigation')

    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4"
        style="background: linear-gradient(135deg, #E0F2F1 0%, #FFF9C4 100%);">
        <div
            class="w-full max-w-md px-8 py-8 bg-white overflow-hidden transition duration-300 ease-in-out hover:-translate-y-1 hover:scale-[1.02] rounded-[32px] shadow-[0_10px_40px_rgba(90,156,181,0.2)] hover:shadow-[0_25px_70px_rgba(147,112,219,0.3)]">
            {{ $slot }}
        </div>
    </div>
</body>

</html>