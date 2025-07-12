<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HRMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-indigo-100 via-white to-indigo-200 min-h-screen">
        <div class="min-h-screen flex flex-col justify-center items-center py-8 px-2">
            <div class="flex flex-col items-center">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-indigo-600" />
                </a>
                <h1 class="mt-4 text-2xl font-bold text-indigo-700 tracking-tight">HR Management System</h1>
            </div>
            <div class="w-full max-w-md mt-8 px-6 py-8 bg-white/90 dark:bg-gray-900/90 shadow-xl rounded-2xl border border-indigo-100 dark:border-gray-800">
                {{ $slot }}
            </div>
            <div class="mt-8 text-xs text-gray-400">&copy; {{ date('Y') }} HRMS. All rights reserved.</div>
        </div>
    </body>
</html>
