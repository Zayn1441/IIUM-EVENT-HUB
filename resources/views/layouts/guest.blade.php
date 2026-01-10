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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#EAF2FF]">
        <div class="flex flex-col items-center mb-6">
            <a href="/" class="mb-4">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center text-white shadow-lg overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="IIUM Event Hub" class="w-full h-full object-contain">
                </div>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">IIUM Event Hub</h2>
            @if (isset($subtitle))
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="w-full sm:max-w-md mt-2 px-8 py-8 bg-white shadow-lg overflow-hidden sm:rounded-2xl">
            {{ $slot }}
        </div>

        <div class="mt-8 text-sm text-gray-500">
            &copy; {{ date('Y') }} International Islamic University Malaysia
        </div>
    </div>
</body>

</html>