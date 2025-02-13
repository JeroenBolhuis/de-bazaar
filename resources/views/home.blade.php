@extends('layouts.app')

@section('title', __('Home'))

@section('content')
<div class="bg-white dark:bg-gray-900">
    <!-- Hero section -->
    <div class="relative isolate overflow-hidden bg-gradient-to-b from-indigo-100/40 dark:from-indigo-900/40">
        <div class="mx-auto max-w-7xl pb-24 pt-10 sm:pb-32 lg:grid lg:grid-cols-2 lg:gap-x-8 lg:px-8 lg:py-40">
            <div class="px-6 lg:px-0 lg:pt-4">
                <div class="mx-auto max-w-2xl">
                    <div class="max-w-lg">
                        <h1 class="mt-10 text-4xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-6xl">
                            {{ __('Your Marketplace for Everything') }}
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-400">
                            {{ __('Buy, sell, rent, and auction items in your local community. Join thousands of users who trust our platform for their marketplace needs.') }}
                        </p>
                        <div class="mt-10 flex items-center gap-x-6">
                            <a href="{{ route('listings.index') }}" class="rounded-md bg-indigo-600 dark:bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                {{ __('Browse Listings') }}
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                {{ __('Create Account') }} <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured sections -->
    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl">{{ __('Featured Categories') }}</h2>
            <p class="mt-2 text-lg leading-8 text-gray-600 dark:text-gray-400">
                {{ __('Discover what\'s popular in our marketplace') }}
            </p>
        </div>

        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            <!-- Listings -->
            <article class="flex flex-col items-start justify-between">
                <div class="relative w-full">
                    <div class="aspect-[16/9] w-full rounded-2xl bg-gray-100 dark:bg-gray-800 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                        <div class="flex items-center justify-center h-full">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                            <a href="{{ route('listings.index') }}">{{ __('Buy & Sell') }}</a>
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('Browse through thousands of items from local sellers') }}</p>
                    </div>
                </div>
            </article>

            <!-- Rentals -->
            <article class="flex flex-col items-start justify-between">
                <div class="relative w-full">
                    <div class="aspect-[16/9] w-full rounded-2xl bg-gray-100 dark:bg-gray-800 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                        <div class="flex items-center justify-center h-full">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                            <a href="{{ route('rentals.index') }}">{{ __('Rentals') }}</a>
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('Rent items for your temporary needs') }}</p>
                    </div>
                </div>
            </article>

            <!-- Auctions -->
            <article class="flex flex-col items-start justify-between">
                <div class="relative w-full">
                    <div class="aspect-[16/9] w-full rounded-2xl bg-gray-100 dark:bg-gray-800 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                        <div class="flex items-center justify-center h-full">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100">
                            <a href="{{ route('auctions.index') }}">{{ __('Auctions') }}</a>
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('Bid on unique items in live auctions') }}</p>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection 