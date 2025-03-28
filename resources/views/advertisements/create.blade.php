<x-app-layout>
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
                    <div id="auction-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                        <div>
                            <x-input-label for="auction_start_date" :value="__('Auction Start Date')" />
                            <x-text-input id="auction_start_date" class="mt-1 block w-full" type="datetime-local" name="auction_start_date" :value="old('auction_start_date')" />
                            <x-input-error :messages="$errors->get('auction_start_date')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="auction_end_date" :value="__('Auction End Date')" />
                            <x-text-input id="auction_end_date" class="mt-1 block w-full" type="datetime-local" name="auction_end_date" :value="old('auction_end_date')" />
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

    <script>
        // Wait for the DOM to be fully loaded
        window.addEventListener('load', function() {
            const typeSelect = document.getElementById('type');
            const auctionFields = document.getElementById('auction-fields');
            const rentalFields = document.getElementById('rental-fields');
            const priceLabel = document.getElementById('price-label');

            function toggleFields() {
                const selectedType = typeSelect.value;
                console.log('Selected type:', selectedType); // Debug log
                
                if (selectedType === 'auction') {
                    auctionFields.style.display = 'grid';
                    rentalFields.style.display = 'none';
                    priceLabel.textContent = 'Starting Price';
                } else if (selectedType === 'rental') {
                    auctionFields.style.display = 'none';
                    rentalFields.style.display = 'grid';
                    priceLabel.textContent = 'Price per day';
                } else {
                    auctionFields.style.display = 'none';
                    rentalFields.style.display = 'none';
                    priceLabel.textContent = 'Price';
                }
            }

            // Add event listener for change
            typeSelect.addEventListener('change', toggleFields);
            
            // Run on initial load
            toggleFields();
        });
    </script>
</x-app-layout>
                        
                        
                        
                        
                        
                        
                    
