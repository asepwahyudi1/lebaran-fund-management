<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 dark:bg-gray-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-linear-to-br from-indigo-50/50 via-white to-purple-50/50 dark:from-slate-950 dark:via-gray-950 dark:to-indigo-950/30">
            <div class="z-10">
                <a href="/" wire:navigate class="flex flex-col items-center gap-2">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 text-white font-bold text-2xl">
                        LS
                    </div>
                    <span class="text-xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Lebaran Sari</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-xl rounded-2xl z-10">
                {{ $slot }}
            </div>
        </div>
        @fluxScripts
    </body>
</html>
