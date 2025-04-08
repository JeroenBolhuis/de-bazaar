<x-app-layout>
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
                                <a href="{{ route('advertisements.index') }}"
                                   class="rounded-md bg-indigo-600 dark:bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    {{ __('Browse Listings') }}
                                </a>
                                @if(!auth()->check())
                                <a href="{{ route('register') }}"
                                   class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                    {{ __('Create Account') }} <span aria-hidden="true">→</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Newest Advertisements Section -->
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl">{{ __('Newest Advertisements') }}</h2>
                <p class="mt-2 text-lg leading-8 text-gray-600 dark:text-gray-400">
                    {{ __('Check out the latest items added by users') }}
                </p>
            </div>

            <!-- Grid -->
            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($advertisements->take(6) as $advertisement)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden relative">
                        <!-- Placeholder image -->
                        <div class="aspect-w-3 aspect-h-2">
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $advertisement->title }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $advertisement->description }}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-indigo-600">€{{ $advertisement->price }}</span>
                                <!-- Optional: Add location or date -->
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 dark:text-gray-300 text-center mt-4">{{ __('No advertisements found.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
