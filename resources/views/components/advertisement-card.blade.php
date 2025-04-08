<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
    <div class="relative">
        @if($advertisement->image)
            <img src="{{ asset('storage/' . $advertisement->image) }}" alt="{{ $advertisement->title }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                <x-heroicon-o-photo class="w-12 h-12 text-gray-400 dark:text-gray-500" />
            </div>
        @endif
        
        @if($showStatus && !$advertisement->is_active)
            <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                {{ __('Inactive') }}
            </div>
        @endif
        
        @if($advertisement->type === 'rental')
            <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                {{ __('Rental') }}
            </div>
        @elseif($advertisement->type === 'auction')
            <div class="absolute top-2 left-2 bg-purple-500 text-white text-xs px-2 py-1 rounded">
                {{ __('Auction') }}
            </div>
        @endif
    </div>
    
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $advertisement->title }}</h3>
        
        @if($showPrice)
            <div class="flex items-center justify-between mb-3">
                <div class="text-gray-600 dark:text-gray-300">
                    @if($advertisement->type === 'rental')
                        <span class="font-medium text-lg">€{{ number_format($advertisement->price, 2) }}</span> / day
                    @elseif($advertisement->type === 'auction')
                        <span class="font-medium text-lg">€{{ number_format($advertisement->price, 2) }}</span> (current bid)
                    @else
                        <span class="font-medium text-lg">€{{ number_format($advertisement->price, 2) }}</span>
                    @endif
                </div>
            </div>
        @endif
        
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
            {{ $advertisement->description }}
        </p>
        
        @if($showActions)
            <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                @if($advertisement->type === 'rental')
                    <a href="{{ route('advertisements.show', $advertisement) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md">
                        <x-heroicon-o-calendar class="w-4 h-4 mr-2" />
                        {{ __('Book Now') }}
                    </a>
                @elseif($advertisement->type === 'auction')
                    <a href="{{ route('advertisements.show', $advertisement) }}" class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium rounded-md">
                        <x-heroicon-o-hand-raised class="w-4 h-4 mr-2" />
                        {{ __('Place Bid') }}
                    </a>
                @else
                    <a href="{{ route('advertisements.show', $advertisement) }}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md">
                        <x-heroicon-o-shopping-cart class="w-4 h-4 mr-2" />
                        {{ __('Buy Now') }}
                    </a>
                @endif
                
                <button type="button" 
                        x-data=""
                        x-on:click="$dispatch('toggle-favorite', { id: {{ $advertisement->id }} })"
                        class="text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-500">
                    <x-heroicon-o-heart class="w-6 h-6" />
                </button>
            </div>
        @endif
    </div>
</div> 