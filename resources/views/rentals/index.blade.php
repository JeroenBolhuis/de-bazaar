@extends('layouts.app')

@section('title', __('Rentals'))

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">{{ __('Rentals') }}</h2>
            <p class="mt-2 text-sm text-gray-700">{{ __('Browse items available for rent') }}</p>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Filters -->
            <div class="w-full md:w-64 flex-none">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-lg font-medium mb-4">{{ __('Filters') }}</h3>
                    
                    <form action="{{ route('rentals.index') }}" method="GET">
                        <!-- Date Range -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Rental Period') }}</label>
                            <div class="space-y-2">
                                <input type="date" name="start_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <input type="date" name="end_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Categories') }}</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="categories[]" value="tools" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Tools') }}</span>
                                </label>
                                <!-- Add more categories -->
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Daily Rate') }}</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <input type="number" name="min_price" placeholder="Min" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <div>
                                    <input type="number" name="max_price" placeholder="Max" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Location') }}</label>
                            <input type="text" name="location" placeholder="Enter location" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            {{ __('Apply Filters') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Rentals Grid -->
            <div class="flex-grow">
                <!-- Sort and View Options -->
                <div class="bg-white p-4 rounded-lg shadow mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <select name="sort" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="availability">{{ __('Available First') }}</option>
                            <option value="price_asc">{{ __('Price: Low to High') }}</option>
                            <option value="price_desc">{{ __('Price: High to Low') }}</option>
                            <option value="rating">{{ __('Highest Rated') }}</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="p-2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <button type="button" class="p-2 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Placeholder for rentals -->
                    @for ($i = 0; $i < 9; $i++)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="aspect-w-3 aspect-h-2">
                            <div class="w-full h-48 bg-gray-200"></div>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Sample Rental Item') }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ __('Available') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">{{ __('Sample description text goes here...') }}</p>
                            <div class="mt-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-green-600">€25/day</span>
                                    <span class="text-sm text-gray-500">{{ __('Amsterdam') }}</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span>4.8 (42 {{ __('reviews') }})</span>
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