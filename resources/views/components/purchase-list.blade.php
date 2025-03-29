@props([
    'sales' => [],
    'rentals' => [],
    'type' => 'purchased', // can be 'purchased' or 'sold'
    'activeTab' => 'sales',
    'sortBy' => 'date',
    'sortDirection' => 'desc',
    'startDate' => null,
    'endDate' => null
])

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-10">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
            {{ $type === 'purchased' ? __('My Purchases') : __('My Sales') }}
        </h2>
        <p class="mt-3 text-base text-gray-600 dark:text-gray-400 max-w-3xl">
            {{ $type === 'purchased' ? __('View your purchase history and rentals') : __('View your sales history and rented out items') }}
        </p>
    </div>

    <!-- Filters -->
    <div class="mb-8">
        <x-date-range-filter :start-date="$startDate" :end-date="$endDate" :active-tab="$activeTab" />
    </div>

    <!-- Tabs -->
    <div class="mb-8 border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route($type === 'purchased' ? 'purchases.index' : 'sales.index', ['active_tab' => 'sales'] + request()->query()) }}" 
               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'sales' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                {{ __('Sales') }}
            </a>
            <a href="{{ route($type === 'purchased' ? 'purchases.index' : 'sales.index', ['active_tab' => 'rentals'] + request()->query()) }}" 
               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'rentals' ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                {{ __('Rentals') }}
            </a>
        </nav>
    </div>

    <!-- Content -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-900/5 dark:ring-gray-800/5">
        @if($activeTab === 'sales')
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ $type === 'purchased' ? __('Purchased Items') : __('Sold Items') }}
                </h2>
            </div>
            @if(count($sales) > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($sales as $sale)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 ease-in-out">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    @if($sale->advertisement->image)
                                        <img src="{{ Storage::url($sale->advertisement->image) }}" alt="{{ $sale->advertisement->title }}" class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 dark:text-gray-500">No image</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $sale->advertisement->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $sale->purchase_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($sale->discount_percentage > 0)
                                        <div class="relative inline-block">
                                            <div class="text-sm text-gray-500 line-through">
                                                €{{ number_format($sale->original_price, 2) }}
                                            </div>
                                            <div class="text-lg font-semibold text-green-500">
                                                €{{ number_format($sale->final_price, 2) }}
                                                <span class="text-xs font-normal text-gray-500">(-{{ $sale->discount_percentage }}%)</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            €{{ number_format($sale->original_price, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">{{ $type === 'purchased' ? __('No purchases found') : __('No sales found') }}</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $type === 'purchased' ? __('When you make purchases, they will appear here.') : __('When you make sales, they will appear here.') }}</p>
                </div>
            @endif
        @else
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ $type === 'purchased' ? __('Rented Items') : __('Rented Out Items') }}
                </h2>
                
                <a href="{{ route($type === 'purchased' ? 'purchases.calendar' : 'sales.calendar') }}" 
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ __('View Calendar') }}
                </a>
            </div>
            @if(count($rentals) > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($rentals as $rental)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150 ease-in-out">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    @if($rental->advertisement->image)
                                        <img src="{{ Storage::url($rental->advertisement->image) }}" alt="{{ $rental->advertisement->title }}" class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-400 dark:text-gray-500">No image</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            {{ $rental->advertisement->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $rental->start_date->format('M d, Y') }} - {{ $rental->end_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($rental->discount_percentage > 0)
                                        <div class="relative inline-block">
                                            <div class="text-sm text-gray-500 line-through">
                                                €{{ number_format($rental->original_price, 2) }}
                                            </div>
                                            <div class="text-lg font-semibold text-green-500">
                                                €{{ number_format($rental->final_price, 2) }}
                                                <span class="text-xs font-normal text-gray-500">(-{{ $rental->discount_percentage }}%)</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            €{{ number_format($rental->original_price, 2) }}/day
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">{{ $type === 'purchased' ? __('No rentals found') : __('No rented out items found') }}</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $type === 'purchased' ? __('When you rent items, they will appear here.') : __('When you rent out items, they will appear here.') }}</p>
                </div>
            @endif
        @endif
    </div>
</div> 