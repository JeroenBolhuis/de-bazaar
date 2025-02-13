@extends('layouts.app')

@section('title', __('Listings'))

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Listings') }}</h2>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Browse all available items for sale') }}</p>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Filters -->
            <div class="w-full md:w-64 flex-none">
                <div class="bg-white p-4 rounded-lg shadow dark:bg-gray-800">
                    <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">{{ __('Filters') }}</h3>
                    
                    <form action="{{ route('listings.index') }}" method="GET">
                        <!-- Categories -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Categories') }}</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="categories[]" value="electronics" class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Electronics') }}</span>
                                </label>
                                <!-- Add more categories -->
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Price Range') }}</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <input type="number" name="min_price" placeholder="Min" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <input type="number" name="max_price" placeholder="Max" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Location') }}</label>
                            <input type="text" name="location" placeholder="Enter location" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 dark:bg-indigo-500 text-white dark:text-gray-900 px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('Apply Filters') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Listings Grid -->
            <div class="flex-grow">
                <!-- Sort and View Options -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <select name="sort" class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="newest">{{ __('Newest First') }}</option>
                            <option value="price_asc">{{ __('Price: Low to High') }}</option>
                            <option value="price_desc">{{ __('Price: High to Low') }}</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <button type="button" class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Placeholder for listings -->
                    @for ($i = 0; $i < 9; $i++)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="aspect-w-3 aspect-h-2">
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700"></div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Sample Listing Title') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Sample description text goes here...') }}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-indigo-600">€99.99</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Amsterdam') }}</span>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    <nav class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between">
                            <button type="button" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                {{ __('Previous') }}
                            </button>
                            <button type="button" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                {{ __('Next') }}
                            </button>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 