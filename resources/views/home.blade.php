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
                                <a href="{{ route('listings.index') }}"
                                   class="rounded-md bg-indigo-600 dark:bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    {{ __('Browse Listings') }}
                                </a>
                                <a href="{{ route('register') }}"
                                   class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                    {{ __('Create Account') }} <span aria-hidden="true">→</span>
                                </a>
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
                @forelse ($advertisements as $advertisement)
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
                        <div
                            class="aspect-[16/9] w-full rounded-2xl bg-gray-100 dark:bg-gray-800 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                            <img
                                src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                alt="Marketplace items displayed on a table"
                                class="w-full h-full object-cover rounded-2xl">
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
                        <div
                            class="aspect-[16/9] w-full rounded-2xl bg-gray-100 dark:bg-gray-800 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                            <img
                                src="https://images.unsplash.com/photo-1530065928592-fb0dc85d2f27?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                alt="Rental equipment" class="w-full h-full object-cover rounded-2xl">
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
                        <div
                            class="aspect-[16/9] w-full rounded-2xl bg-gray-100 dark:bg-gray-800 object-cover sm:aspect-[2/1] lg:aspect-[3/2]">
                            <img
                                src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                alt="Auction gavel" class="w-full h-full object-cover rounded-2xl">
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
</x-app-layout>
