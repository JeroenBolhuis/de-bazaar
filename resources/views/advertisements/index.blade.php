<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('All Advertisements') }}</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Browse all available items, including listings, rentals, and auctions') }}</p>
                </div>
                @if(auth()->user()?->canSell)
                    <div class="flex space-x-4">
                        <a href="{{ route('advertisements.import') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            {{ __('Import CSV') }}
                        </a>
                        <a href="{{ route('advertisements.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            {{ __('Create Advertisement') }}
                        </a>
                    </div>
                @endif
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <!-- Filters -->
                <div class="w-full md:w-64 flex-none">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">{{ __('Filters') }}</h3>

                        <form action="{{ route('advertisements.index') }}" method="GET">
                            <!-- Advertisement Types -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Types') }}</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="types[]" value="listing" {{ in_array('listing', request('types', [])) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Listings') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="types[]" value="rental" {{ in_array('rental', request('types', [])) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700 text-green-600 shadow-sm">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Rentals') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="types[]" value="auction" {{ in_array('auction', request('types', [])) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Auctions') }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Price Range') }}</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                    <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                </div>
                            </div>

                            <!-- Auction Status (only shown if auction type is selected) -->
                            <div class="mb-4" x-data="{ showAuctionFilters: {{ in_array('auction', request('types', [])) ? 'true' : 'false' }} }">
                                <template x-if="showAuctionFilters">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Auction Status') }}</label>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="auction_status[]" value="live" {{ in_array('live', request('auction_status', [])) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm">
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Live Now') }}</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" name="auction_status[]" value="upcoming" {{ in_array('upcoming', request('auction_status', [])) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm">
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Upcoming') }}</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" name="auction_status[]" value="ending_soon" {{ in_array('ending_soon', request('auction_status', [])) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm">
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Ending Soon') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Rental Period (only shown if rental type is selected) -->
                            <div class="mb-4" x-data="{ showRentalFilters: {{ in_array('rental', request('types', [])) ? 'true' : 'false' }} }">
                                <template x-if="showRentalFilters">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Rental Period') }}</label>
                                        <div class="space-y-2">
                                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Apply Filters') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Advertisements Grid -->
                <div class="flex-grow">
                    <!-- Sort Options -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <form method="GET" action="{{ route('advertisements.index') }}">
                                <!-- Preserve all existing filter parameters -->
                                @foreach(request()->except(['sort', '_token']) as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $arrayValue)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $arrayValue }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach

                                <select name="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest First') }}</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>{{ __('Most Popular') }}</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($advertisements as $advertisement)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden relative">
                                <!-- Link to Detail Page -->
                                <a dusk="advertisements-show" href="{{ route('advertisements.show', $advertisement->id) }}" class="block hover:opacity-90 transition">
                                    <!-- Advertisement Image -->
                                    <div class="aspect-w-3 aspect-h-2">
                                        @if($advertisement->image)
                                            <img src="{{ Storage::url($advertisement->image) }}" alt="{{ $advertisement->title }}" class="w-full h-48 object-cover">
                                        @else
                                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <span class="text-gray-400 dark:text-gray-500">No image</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Advertisement Content -->
                                    <div class="p-4">
                                        <!-- Type Badge -->
                                        <div class="mb-2">
                                            @if($advertisement->type === 'listing')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ __('Listing') }}
                                                </span>
                                            @elseif($advertisement->type === 'rental')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    {{ __('Rental') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    {{ __('Auction') }}
                                                </span>
                                            @endif
                                        </div>

                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $advertisement->title }}</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($advertisement->description, 100) }}</p>

                                        <div class="mt-4 flex items-center justify-between">
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Price') }}</p>
                                                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                    €{{ number_format($advertisement->highestBidOrPrice->amount, 2) }}
                                                    @if($advertisement->type === 'rental')
                                                        /{{ __('day') }}
                                                    @endif
                                                </p>
                                            </div>
                                            @if($advertisement->type === 'auction')
                                                <div class="text-right">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Time Left') }}</p>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ \Carbon\Carbon::parse($advertisement->auction_end_date)->diffForHumans() }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                <!-- Favorite Button -->
                                @auth
                                    <div dusk="favorite-button-{{ $advertisement->id }}"
                                        x-data="{ favorited: {{ auth()->user()->favorites->contains($advertisement) ? 'true' : 'false' }}, loading: false }"
                                        class="absolute top-2 right-2"
                                    >
                                        <button
                                            @click.prevent="
                                                loading = true;
                                                fetch('{{ route('advertisements.favorite', $advertisement->id) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Accept': 'application/json',
                                                    },
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    favorited = data.favorited;
                                                    loading = false;
                                                })
                                                .catch(() => {
                                                    loading = false;
                                                    // Keep the current state if there's an error
                                                });
                                            "
                                            class="focus:outline-none transition-transform transform hover:scale-110"
                                            :class="{ 'pointer-events-none': loading }"
                                        >
                                            <template x-if="loading">
                                                <svg class="w-6 h-6 animate-pulse text-red-300 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                </svg>
                                            </template>
                                            <template x-if="!loading && favorited">
                                                <svg class="w-6 h-6 text-red-500 fill-current transition-all duration-300 ease-in-out" viewBox="0 0 24 24">
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                </svg>
                                            </template>
                                            <template x-if="!loading && !favorited">
                                                <svg class="w-6 h-6 text-gray-500 stroke-current fill-none transition-all duration-300 ease-in-out" viewBox="0 0 24 24" stroke-width="1.5">
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                </svg>
                                            </template>
                                        </button>
                                    </div>
                                @endauth
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-600 dark:text-gray-300">{{ __('No advertisements found matching your criteria.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $advertisements->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Update filter visibility based on type selection
        document.addEventListener('DOMContentLoaded', function() {
            const typeCheckboxes = document.querySelectorAll('input[name="types[]"]');
            const auctionFilters = document.querySelector('[x-data*="showAuctionFilters"]');
            const rentalFilters = document.querySelector('[x-data*="showRentalFilters"]');

            if (auctionFilters && rentalFilters) {
                typeCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const showAuction = document.querySelector('input[name="types[]"][value="auction"]').checked;
                        const showRental = document.querySelector('input[name="types[]"][value="rental"]').checked;

                        if (auctionFilters.__x) {
                            auctionFilters.__x.$data.showAuctionFilters = showAuction;
                        }

                        if (rentalFilters.__x) {
                            rentalFilters.__x.$data.showRentalFilters = showRental;
                        }
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
