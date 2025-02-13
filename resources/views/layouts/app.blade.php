<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'De Bazaar') }} - @yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-100 mt-auto">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            © {{ date('Y') }} {{ config('app.name', 'De Bazaar') }}. {{ __('All rights reserved.') }}
                        </div>
                        <div class="flex space-x-6">
                            <a href="{{ route('about') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                {{ __('About') }}
                            </a>
                            <a href="{{ route('contact') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                {{ __('Contact') }}
                            </a>
                            <a href="{{ route('privacy') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                {{ __('Privacy Policy') }}
                            </a>
                            <a href="{{ route('terms') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                {{ __('Terms of Service') }}
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
