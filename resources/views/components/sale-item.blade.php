@props(['sale', 'type' => 'purchased']) {{-- type can be 'purchased' or 'sold' --}}

<div class="p-6 flex flex-col sm:flex-row justify-between gap-6 group">
    <div class="flex-grow min-w-0">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <h4 class="font-medium text-gray-900 dark:text-gray-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">
                    {{ $sale->advertisement->title }}
                </h4>
                <div class="mt-2 flex flex-col sm:flex-row sm:items-center gap-3">
                    <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $type === 'purchased' ? 'Purchased' : 'Sold' }}: {{ $sale->purchase_date->format('M d, Y') }}
                    </span>
                    <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $type === 'purchased' ? 'Seller' : 'Buyer' }}: {{ $type === 'purchased' ? $sale->advertisement->user->name : $sale->user->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center">
        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-transform duration-200">
            €{{ number_format($sale->advertisement->price, 2) }}
        </span>
    </div>
</div> 