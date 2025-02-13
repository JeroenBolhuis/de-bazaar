@extends('layouts.app')

@section('title', __('Auctions'))

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">{{ __('Auctions') }}</h2>
            <p class="mt-2 text-sm text-gray-700">{{ __('Bid on unique items in live auctions') }}</p>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Filters -->
            <div class="w-full md:w-64 flex-none">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium mb-4">{{ __('Filters') }}</h3>
                    
                    <form action="{{ route('auctions.index') }}" method="GET">
                        <!-- Status -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="status[]" value="live" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Live Now') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="status[]" value="upcoming" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Upcoming') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="status[]" value="ending_soon" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Ending Soon') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Categories') }}</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="categories[]" value="collectibles" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Collectibles') }}</span>
                                </label>
                                <!-- Add more categories -->
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Current Bid Range') }}</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <input type="number" name="min_price" placeholder="Min" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <input type="number" name="max_price" placeholder="Max" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            {{ __('Apply Filters') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Auctions Grid -->
            <div class="flex-grow">
                <!-- Featured Auction -->
                <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                    <div class="aspect-w-16 aspect-h-9">
                        <div class="w-full h-64 bg-gray-200"></div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ __('Featured Auction') }}</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ __('Ending in 2h 45m') }}
                            </span>
                        </div>
                        <p class="text-gray-500 mb-4">{{ __('Featured auction description goes here...') }}</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Current Bid') }}</p>
                                <p class="text-2xl font-bold text-purple-600">€1,250</p>
                            </div>
                            <button class="bg-purple-600 text-white px-6 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                {{ __('Place Bid') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sort and View Options -->
                <div class="bg-white p-4 rounded-lg shadow mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <select name="sort" class="rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <option value="ending_soon">{{ __('Ending Soon') }}</option>
                            <option value="most_bids">{{ __('Most Bids') }}</option>
                            <option value="price_asc">{{ __('Price: Low to High') }}</option>
                            <option value="price_desc">{{ __('Price: High to Low') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Placeholder for auctions -->
                    @for ($i = 0; $i < 6; $i++)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="aspect-w-3 aspect-h-2">
                            <div class="w-full h-48 bg-gray-200"></div>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Sample Auction Item') }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ __('Live') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">{{ __('Sample description text goes here...') }}</p>
                            <div class="mt-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500">{{ __('Current Bid') }}</p>
                                        <p class="text-lg font-bold text-purple-600">€99</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">{{ __('Time Left') }}</p>
                                        <p class="text-sm font-medium text-gray-900">1h 30m</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                        {{ __('Bid Now') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    <nav class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between">
                            <button type="button" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                {{ __('Previous') }}
                            </button>
                            <button type="button" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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