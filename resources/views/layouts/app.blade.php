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
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('home') }}">
                                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                                    {{ __('Home') }}
                                </x-nav-link>
                                <x-nav-link :href="route('listings.index')" :active="request()->routeIs('listings.*')">
                                    {{ __('Listings') }}
                                </x-nav-link>
                                <x-nav-link :href="route('rentals.index')" :active="request()->routeIs('rentals.*')">
                                    {{ __('Rentals') }}
                                </x-nav-link>
                                <x-nav-link :href="route('auctions.index')" :active="request()->routeIs('auctions.*')">
                                    {{ __('Auctions') }}
                                </x-nav-link>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            @auth
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ Auth::user()->name }}</div>
                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('dashboard')">
                                            {{ __('Dashboard') }}
                                        </x-dropdown-link>
                                        @if(Auth::user()->isBusinessUser())
                                            <x-dropdown-link :href="route('company.settings')">
                                                {{ __('Company Settings') }}
                                            </x-dropdown-link>
                                        @endif
                                        <div class="border-t border-gray-200"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">{{ __('Login') }}</a>
                                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">{{ __('Register') }}</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

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
