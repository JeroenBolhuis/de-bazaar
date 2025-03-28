<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-8">{{ __('Create Advertisement') }}</h2>


            @if ($errors->has('limit'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ $errors->first('limit') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('advertisements.store') }}" class="mt-3 bg-white dark:bg-gray-800 rounded-lg shadow p-6" enctype="multipart/form-data">
                @csrf
                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="type" :value="__('Type')" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                            <option value="listing" {{ old('type') == 'listing' ? 'selected' : '' }}>Listing</option>
                            <option value="rental" {{ old('type') == 'rental' ? 'selected' : '' }}>Rental</option>
                            <option value="auction" {{ old('type') == 'auction' ? 'selected' : '' }}>Auction</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        
                        @auth
                            <div id="remaining-counter" class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                @php
                                    $listingCount = App\Models\Advertisement::where('user_id', auth()->id())
                                                    ->where('type', 'listing')
                                                    ->count();
                                    $rentalCount = App\Models\Advertisement::where('user_id', auth()->id())
                                                    ->where('type', 'rental')
                                                    ->count();
                                    $auctionCount = App\Models\Advertisement::where('user_id', auth()->id())
                                                    ->where('type', 'auction')
                                                    ->count();
                                    
                                    $remainingListing = 4 - $listingCount;
                                    $remainingRental = 4 - $rentalCount;
                                    $remainingAuction = 4 - $auctionCount;
                                @endphp
                                <span id="listing-count" class="{{ old('type') == 'listing' || old('type') == null ? '' : 'hidden' }}">
                                    You have {{ $remainingListing }} listing{{ $remainingListing !== 1 ? 's' : '' }} remaining (maximum 4)
                                    @if($remainingListing <= 0)
                                        <span class="text-red-500 font-bold">- Limit reached!</span>
                                    @endif
                                </span>
                                <span id="rental-count" class="{{ old('type') == 'rental' ? '' : 'hidden' }}">
                                    You have {{ $remainingRental }} rental{{ $remainingRental !== 1 ? 's' : '' }} remaining (maximum 4)
                                    @if($remainingRental <= 0)
                                        <span class="text-red-500 font-bold">- Limit reached!</span>
                                    @endif
                                </span>
                                <span id="auction-count" class="{{ old('type') == 'auction' ? '' : 'hidden' }}">
                                    You have {{ $remainingAuction }} auction{{ $remainingAuction !== 1 ? 's' : '' }} remaining (maximum 4)
                                    @if($remainingAuction <= 0)
                                        <span class="text-red-500 font-bold">- Limit reached!</span>
                                    @endif
                                </span>
                            </div>
                        @endauth
                    </div>

                    <div>
                        <x-input-label for="title" :value="__('Title *')" />
                        <x-text-input id="title" class="mt-1 block w-full" type="text" name="title" :value="old('title')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="md:row-span-2">
                        <x-input-label for="description" :value="__('Description')" />
                        <x-textarea-input id="description" class="mt-1 block w-full h-32" name="description" :value="old('description')"/>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label for="image" :value="__('Image')" />
                        <input type="file" id="image" name="image" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100
                            dark:file:bg-indigo-900 dark:file:text-indigo-300"/>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label for="price" :value="__('Price *')" id="price-label" />
                        <x-text-input id="price" class="mt-1 block w-full" type="number" name="price" :value="old('price') ?? 0" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <!-- Auction specific fields -->
                    <div id="auction-fields" class="md:col-span-2" style="display: none;">
                        <div class="space-y-4">
                            <!-- Start Now Toggle -->
                            <div class="flex items-center">
                                <input type="checkbox" id="start_now" name="start_now" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700" {{ old('start_now') ? 'checked' : '' }}>
                                <label for="start_now" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Start auction now</label>
                            </div>
                            
                            <!-- Date Range Picker (shown when start_now is unchecked) -->
                            <div id="date_range_container">
                                <x-input-label for="auction_date_range" :value="__('Auction Period')" />
                                <div class="relative mt-1">
                                    <input
                                        type="text"
                                        id="auction_date_range"
                                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 pl-10"
                                        placeholder="Select auction period..."
                                        readonly
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- End Date Only Picker (shown when start_now is checked) -->
                            <div id="end_date_container" style="display:none;">
                                <x-input-label for="auction_end_only" :value="__('Auction End Date & Time')" />
                                <div class="relative mt-1">
                                    <input
                                        type="text"
                                        id="auction_end_only"
                                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 pl-10"
                                        placeholder="Select end date and time..."
                                        readonly
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="auction_start_date" id="auction_start_date" value="{{ old('auction_start_date') }}">
                            <input type="hidden" name="auction_end_date" id="auction_end_date" value="{{ old('auction_end_date') }}">
                            <x-input-error :messages="$errors->get('auction_start_date')" class="mt-2" />
                            <x-input-error :messages="$errors->get('auction_end_date')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Rental specific fields -->
                    <div id="rental-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                        <div>
                            <x-input-label for="condition" :value="__('Condition (%)')" />
                            <x-text-input id="condition" class="mt-1 block w-full" type="number" name="condition" :value="old('condition')" min="0" max="100" />
                            <x-input-error :messages="$errors->get('condition')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="wear_per_day" :value="__('Wear per Day (%)')" />
                            <x-text-input id="wear_per_day" class="mt-1 block w-full" type="number" name="wear_per_day" :value="old('wear_per_day')" min="0" max="100" />
                            <x-input-error :messages="$errors->get('wear_per_day')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <button type="submit" class="mt-6 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Create Listing') }}
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const typeSelect = document.getElementById('type');
                const auctionFields = document.getElementById('auction-fields');
                const rentalFields = document.getElementById('rental-fields');
                const priceLabel = document.getElementById('price-label');
                
                // Counter elements
                const listingCount = document.getElementById('listing-count');
                const rentalCount = document.getElementById('rental-count');
                const auctionCount = document.getElementById('auction-count');

                // Auction fields
                const startNowToggle = document.getElementById('start_now');
                const dateRangeContainer = document.getElementById('date_range_container');
                const endDateContainer = document.getElementById('end_date_container');
                const auctionStartDate = document.getElementById('auction_start_date');
                const auctionEndDate = document.getElementById('auction_end_date');

                function toggleFields() {
                    const selectedType = typeSelect.value;
                    
                    if (selectedType === 'auction') {
                        auctionFields.style.display = 'block';
                        rentalFields.style.display = 'none';
                        priceLabel.textContent = 'Starting Price';
                        
                        // Update counter visibility
                        if (listingCount) listingCount.classList.add('hidden');
                        if (rentalCount) rentalCount.classList.add('hidden');
                        if (auctionCount) auctionCount.classList.remove('hidden');
                    } else if (selectedType === 'rental') {
                        auctionFields.style.display = 'none';
                        rentalFields.style.display = 'grid';
                        priceLabel.textContent = 'Price per day';
                        
                        // Update counter visibility
                        if (listingCount) listingCount.classList.add('hidden');
                        if (rentalCount) rentalCount.classList.remove('hidden');
                        if (auctionCount) auctionCount.classList.add('hidden');
                    } else {
                        auctionFields.style.display = 'none';
                        rentalFields.style.display = 'none';
                        priceLabel.textContent = 'Price';
                        
                        // Update counter visibility
                        if (listingCount) listingCount.classList.remove('hidden');
                        if (rentalCount) rentalCount.classList.add('hidden');
                        if (auctionCount) auctionCount.classList.add('hidden');
                    }
                }

                function toggleStartNow() {
                    if (startNowToggle.checked) {
                        dateRangeContainer.style.display = 'none';
                        endDateContainer.style.display = 'block';
                        
                        // Set start date to now
                        auctionStartDate.value = new Date().toISOString().slice(0, 19).replace('T', ' ');
                    } else {
                        dateRangeContainer.style.display = 'block';
                        endDateContainer.style.display = 'none';
                        
                        // Clear start date if no date range is selected
                        if (document.getElementById('auction_date_range')._flatpickr && 
                            !document.getElementById('auction_date_range')._flatpickr.selectedDates.length) {
                            auctionStartDate.value = '';
                        }
                    }
                }

                // Add event listeners
                typeSelect.addEventListener('change', toggleFields);
                if (startNowToggle) {
                    startNowToggle.addEventListener('change', toggleStartNow);
                }
                
                // Run on initial load
                toggleFields();
                if (startNowToggle) {
                    toggleStartNow();
                }

                // Initialize date pickers
                if (document.getElementById('auction_date_range')) {
                    flatpickr("#auction_date_range", {
                        mode: "range",
                        dateFormat: "d/m/Y H:i",
                        enableTime: true,
                        minDate: "today",
                        time_24hr: true,
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                        onChange: function(selectedDates) {
                            if (selectedDates.length === 2) {
                                document.getElementById('auction_start_date').value = flatpickr.formatDate(selectedDates[0], "Y-m-d H:i:s");
                                document.getElementById('auction_end_date').value = flatpickr.formatDate(selectedDates[1], "Y-m-d H:i:s");
                            } else {
                                if (!startNowToggle.checked) {
                                    document.getElementById('auction_start_date').value = '';
                                }
                                document.getElementById('auction_end_date').value = '';
                            }
                        }
                    });
                    
                    // Set default values if old values exist
                    const startDate = document.getElementById('auction_start_date').value;
                    const endDate = document.getElementById('auction_end_date').value;
                    
                    if (startDate && endDate && !startNowToggle.checked) {
                        const dateRangePicker = document.getElementById('auction_date_range')._flatpickr;
                        dateRangePicker.setDate([startDate, endDate]);
                    }
                }

                if (document.getElementById('auction_end_only')) {
                    flatpickr("#auction_end_only", {
                        enableTime: true,
                        dateFormat: "d/m/Y H:i",
                        minDate: "today",
                        time_24hr: true,
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                        onChange: function(selectedDates) {
                            if (selectedDates.length === 1) {
                                document.getElementById('auction_end_date').value = flatpickr.formatDate(selectedDates[0], "Y-m-d H:i:s");
                            } else {
                                document.getElementById('auction_end_date').value = '';
                            }
                        }
                    });

                    // Set default value for end date if it exists
                    const endDate = document.getElementById('auction_end_date').value;
                    if (endDate && startNowToggle.checked) {
                        const endDatePicker = document.getElementById('auction_end_only')._flatpickr;
                        endDatePicker.setDate(endDate);
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
                        
                        
                        
                        
                        
                        
                    
