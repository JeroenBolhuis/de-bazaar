<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Import Advertisements') }}</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Upload a CSV file to create multiple advertisements at once.') }}</p>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('advertisements.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="csv_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('CSV File') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                id="csv_file" 
                                name="csv_file" 
                                accept=".csv"
                                required
                                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100
                                    dark:file:bg-gray-700 dark:file:text-gray-200"
                            >
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Upload a CSV file with the required fields.') }}
                            </p>
                        </div>

                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Images') }}
                            </label>
                            <input type="file" 
                                id="images" 
                                name="images[]" 
                                accept="image/*"
                                multiple
                                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100
                                    dark:file:bg-gray-700 dark:file:text-gray-200"
                            >
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Upload the images referenced in your CSV file. The filenames should match those in the CSV.') }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Import') }}
                            </button>
                            <a href="{{ route('advertisements.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('CSV Format Instructions') }}</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ __('Your CSV file must include the following columns:') }}</p>
                            <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <li>title <span class="text-red-500">*</span> {{ __('(required)') }}</li>
                                <li>description <span class="text-red-500">*</span> {{ __('(required)') }}</li>
                                <li>price <span class="text-red-500">*</span> {{ __('(required, numeric)') }}</li>
                                <li>type <span class="text-red-500">*</span> {{ __('(required: sale, auction, or rental)') }}</li>
                                <li>condition <span class="text-red-500">*</span> {{ __('(required)') }}</li>
                                <li>auction_start_date {{ __('(required for auction type, format: YYYY-MM-DD HH:mm:ss)') }}</li>
                                <li>auction_end_date {{ __('(required for auction type, format: YYYY-MM-DD HH:mm:ss)') }}</li>
                                <li>wear_per_day {{ __('(optional)') }}</li>
                                <li>image {{ __('(optional, filename of the image to upload)') }}</li>
                                <li>is_active {{ __('(optional, 1 for active, 0 for inactive)') }}</li>
                            </ul>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Example CSV rows:') }}<br>
                                <code class="bg-gray-100 dark:bg-gray-900 px-2 py-1 rounded text-xs block whitespace-pre-wrap mt-2">
{{ __('For a listing:') }}
title,description,price,type,condition,wear_per_day,auction_start_date,auction_end_date,image
"My Item","A great item for sale",99.99,listing,,,,,item.jpg

{{ __('For a rental:') }}
title,description,price,type,condition,wear_per_day,auction_start_date,auction_end_date,image
"Rental Item","Available for rent",25.00,rental,95,2,,,rental.jpg

{{ __('For an auction:') }}
title,description,price,type,condition,wear_per_day,auction_start_date,auction_end_date,image
"Auction Item","Bid now!",50.00,auction,,,2024-03-20 14:00:00,2024-03-27 14:00:00,auction.jpg</code>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 