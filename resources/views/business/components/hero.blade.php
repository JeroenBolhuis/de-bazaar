@props(['business'])
<div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 bg-gradient-to-r from-indigo-600 to-indigo-800 dark:from-indigo-900 dark:to-indigo-950">
    <div class="text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
            {{ $business->name }}
        </h1>
        <p class="mt-6 text-xl text-indigo-100 max-w-3xl mx-auto">
            {{ __('Welcome to our business page. Explore our products and services.') }}
        </p>
    </div>
</div>