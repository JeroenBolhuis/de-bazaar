<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-6">{{ __('Dashboard') }}</h2>

                    <!-- Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-indigo-50 dark:bg-indigo-900 rounded-lg p-6">
                            <div class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">{{ __('Active Listings') }}</div>
                            <div class="mt-2 text-3xl font-semibold">0</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900 rounded-lg p-6">
                            <div class="text-green-600 dark:text-green-400 text-sm font-medium">{{ __('Active Rentals') }}</div>
                            <div class="mt-2 text-3xl font-semibold">0</div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-6">
                            <div class="text-purple-600 dark:text-purple-400 text-sm font-medium">{{ __('Active Auctions') }}</div>
                            <div class="mt-2 text-3xl font-semibold">0</div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">{{ __('Quick Actions') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('listings.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create Listing') }}
                            </a>
                            <a href="{{ route('rentals.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create Rental') }}
                            </a>
                            <a href="{{ route('auctions.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create Auction') }}
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">{{ __('Recent Activity') }}</h3>
                        <div class="bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700">
                            <div class="p-4 text-gray-500 dark:text-gray-400 text-sm">
                                {{ __('No recent activity') }}
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">{{ __('Upcoming Events') }}</h3>
                        <div class="bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700">
                            <div class="divide-y dark:divide-gray-700">
                                <div class="p-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Rental Returns') }}</div>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('No upcoming rental returns') }}</div>
                                </div>
                                <div class="p-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Ending Auctions') }}</div>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('No ending auctions') }}</div>
                                </div>
                                <div class="p-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Expiring Listings') }}</div>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('No expiring listings') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->isBusiness())
                    <!-- Business Stats -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">{{ __('Business Statistics') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="text-sm font-medium text-gray-500">{{ __('Total Revenue') }}</div>
                                <div class="mt-1 text-2xl font-semibold">€0.00</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="text-sm font-medium text-gray-500">{{ __('Active Contracts') }}</div>
                                <div class="mt-1 text-2xl font-semibold">0</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="text-sm font-medium text-gray-500">{{ __('Total Orders') }}</div>
                                <div class="mt-1 text-2xl font-semibold">0</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border">
                                <div class="text-sm font-medium text-gray-500">{{ __('Customer Rating') }}</div>
                                <div class="mt-1 text-2xl font-semibold">-</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
