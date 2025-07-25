<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Perpus Itaru') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-20 sm:pt-0 bg-gray-100">
        <div>
            {{-- LOGO CUSTOM --}}
            <a href="/">
                <img src="{{ asset('images/logo.jpg') }}" alt="E-Perpus Itaru Logo" class="w-20 h-20 fill-current text-gray-500 mx-auto">
            </a>
        </div>


        {{-- CARD LOGIN/REGISTER --}}
        <div class="w-full max-w-sm sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"> {{-- TAMBAHKAN max-w-sm --}}
            {{ $slot }}
        </div>
    </div>
</body>

</html>