<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-gray-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sumber Sari') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="h-full font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-950" x-data="{ sidebarOpen: false }">
        <div class="min-h-full flex">
            <!-- Sidebar for Mobile (Drawer) -->
            <div x-show="sidebarOpen" class="relative z-50 lg:hidden" style="display: none;" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80"></div>

                <div class="fixed inset-0 flex">
                    <!-- Drawer Content -->
                    <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative mr-16 flex w-full max-w-xs flex-1 flex-col bg-indigo-950 text-white p-6">
                        <!-- Close button -->
                        <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                            <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5 text-gray-400 hover:text-white">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Logo -->
                        <div class="flex items-center gap-3 pb-6 border-b border-indigo-900">
                            <div class="w-10 h-10 rounded-xl bg-white text-indigo-950 flex items-center justify-center font-black text-lg shadow-md">
                                SS
                            </div>
                            <div class="flex flex-col">
                                <span class="text-lg font-black tracking-tight leading-none text-white">Sumber Sari</span>
                                <span class="text-[9px] font-bold tracking-widest uppercase text-indigo-300 mt-1">Paket Lebaran</span>
                            </div>
                        </div>

                        <!-- Sidebar menu links -->
                        <div class="mt-6 flex-1 overflow-y-auto">
                            @include('layouts.navigation-links')
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar for Desktop -->
            <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0 lg:z-40 bg-indigo-950 text-white border-r border-indigo-900/30 p-5">
                <!-- Logo -->
                <div class="flex items-center gap-3 pb-5 border-b border-indigo-900/60">
                    <div class="w-10 h-10 rounded-xl bg-white text-indigo-950 flex items-center justify-center font-black text-lg shadow-md">
                        SS
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base font-black tracking-tight leading-none text-white">Sumber Sari</span>
                        <span class="text-[9px] font-bold tracking-widest uppercase text-indigo-300 mt-1">Paket Lebaran</span>
                    </div>
                </div>

                <!-- Sidebar menu links -->
                <div class="mt-6 flex-1 overflow-y-auto">
                    @include('layouts.navigation-links')
                </div>
            </div>

            <!-- Main Panel -->
            <div class="lg:pl-64 flex flex-col flex-1 w-0">
                <!-- Top Header -->
                <div class="sticky top-0 z-40 flex h-16 flex-shrink-0 bg-white dark:bg-gray-900 border-b border-gray-150 dark:border-gray-800 px-4 sm:px-6 lg:px-8 items-center justify-between shadow-xs">
                    <!-- Hamburger / Menu Toggle -->
                    <button type="button" @click="sidebarOpen = true" class="p-2 -ml-2 text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 lg:hidden focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Title or Page Name Slot -->
                    <div class="flex-1 min-w-0 pr-4">
                        @if (isset($header))
                            {{ $header }}
                        @endif
                    </div>

                    <!-- User Profile Area -->
                    <div class="flex items-center">
                        <livewire:layout.navigation />
                    </div>
                </div>

                <!-- Main Content Area -->
                <main class="flex-1 overflow-y-auto focus:outline-none py-6 px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @fluxScripts
    </body>
</html>
