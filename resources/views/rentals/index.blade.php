<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Rentals') }}</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Browse items available for rent') }}</p>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <!-- Filters -->
                <div class="w-full md:w-64 flex-none">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">{{ __('Filters') }}</h3>

                        <form action="{{ route('rentals.index') }}" method="GET">
                            <!-- Date Range -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Rental Period') }}</label>
                                <div class="space-y-2">
                                    <input type="date" name="start_date"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                    <input type="date" name="end_date"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                </div>
                            </div>

                            <!-- Categories -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Categories') }}</label>
                                <!-- Example category -->
                                <label class="flex items-center">
                                    <input type="checkbox" name="categories[]" value="tools"
                                           class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Tools') }}</span>
                                </label>
                                <!-- Add more categories here -->
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Daily Rate') }}</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" placeholder="Min"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                    <input type="number" name="max_price" placeholder="Max"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                {{ __('Apply Filters') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Rentals Grid -->
                <div class="flex-grow">
                    <!-- Sort Options -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <form method="GET" action="{{ route('rentals.index') }}">
                                <select name="sort" onchange="this.form.submit()"
                                        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                    <option value="availability">{{ __('Available First') }}</option>
                                    <option value="price_asc">{{ __('Price: Low to High') }}</option>
                                    <option value="price_desc">{{ __('Price: High to Low') }}</option>
                                    <option value="rating">{{ __('Highest Rated') }}</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($rentals as $rental)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                                <div class="aspect-w-3 aspect-h-2">
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700"></div>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $rental->title }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $rental->description }}</p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="text-lg font-bold text-green-600">€{{ $rental->price }}/day</span>
                                    </div>

                                    <!-- Reviews -->
                                    @if($rental->reviews->count())
                                        <div class="mt-4">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                Reviews:</h4>
                                            @foreach($rental->reviews as $review)
                                                <div class="mt-2">
                                                    <span class="text-gray-700 dark:text-gray-300">{{ $review->user->name }}:</span>
                                                    <span>{{ str_repeat('⭐', $review->rating) }}</span>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->comment }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 dark:text-gray-300">{{ __('No rental advertisements found.') }}</p>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $rentals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
