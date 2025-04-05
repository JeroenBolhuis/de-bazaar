<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-6">{{ __('Dashboard') }}</h2>

                    <!-- Stats -->
                    <div class="grid grid-cols-4 gap-2 md:gap-6 mb-8">
                        <div class="bg-indigo-50 dark:bg-indigo-900 rounded-lg p-2 md:p-6">
                            <div class="text-indigo-600 dark:text-indigo-400 text-xs md:text-sm font-medium">{{ __('Active Listings') }}</div>
                            <div class="mt-2 md:text-3xl font-semibold">{{ $stats['active_listings'] }} / 4</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900 rounded-lg p-2 md:p-6">
                            <div class="text-green-600 dark:text-green-400 text-xs md:text-sm font-medium">{{ __('Active Rentals') }}</div>
                            <div class="mt-2 md:text-3xl font-semibold">{{ $stats['active_rentals'] }} / 4</div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-2 md:p-6">
                            <div class="text-purple-600 dark:text-purple-400 text-xs md:text-sm font-medium">{{ __('Active Auctions') }}</div>
                            <div class="mt-2 md:text-3xl font-semibold">{{ $stats['active_auctions'] }} / 4</div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900 rounded-lg p-2 md:p-6">
                            <div class="text-red-600 dark:text-red-400 text-xs md:text-sm font-medium">{{ __('Active Biddings') }}</div>
                            <div class="mt-2 md:text-3xl font-semibold">{{ $stats['active_biddings'] }} / 4</div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">{{ __('Quick Actions') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('advertisements.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create Advertisement') }}
                            </a>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">{{ __('Upcoming Events') }}</h3>
                        <div class="bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700">
                            <div class="divide-y dark:divide-gray-700">
                                <!-- Rental Pickups -->
                                <div class="p-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Rental Pickups') }}</div>
                                    <div class="overflow-y-auto max-h-[250px] pr-4">
                                        @if(count($upcomingEvents['rental_pickups']) > 0)
                                            @foreach($upcomingEvents['rental_pickups'] as $rental)
                                                <x-upcoming-event-item 
                                                    :title="$rental->advertisement->title"
                                                    :date="$rental->start_date"
                                                    :otheruser="$rental->advertisement->user"
                                                    :otheruser_name="'at ' . $rental->advertisement->user->name"
                                                    :days-until="now()->startOfDay()->diffInDays($rental->start_date->startOfDay())"
                                                />
                                            @endforeach
                                        @else
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('No upcoming rental pickups') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Rental Returns -->
                                <div class="p-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Rental Returns') }}</div>
                                    <div class="overflow-y-auto max-h-[250px] pr-4">
                                        @if(count($upcomingEvents['rental_returns']) > 0)
                                            @foreach($upcomingEvents['rental_returns'] as $rental)
                                                <x-upcoming-event-item 
                                                    :title="$rental->advertisement->title"
                                                    :date="$rental->end_date"
                                                    :otheruser="$rental->advertisement->user"
                                                    :otheruser_name="'at ' . $rental->advertisement->user->name"
                                                    :days-until="now()->startOfDay()->diffInDays($rental->end_date->startOfDay())"
                                                />
                                            @endforeach
                                        @else
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('No upcoming rental returns') }}</div>
                                        @endif
                                    </div>
                                </div>
                                @if(auth()->user()->getCanSellAttribute())
                                    <!-- Rental Gives -->
                                    <div class="p-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Items to Give') }}</div>
                                        <div class="overflow-y-auto max-h-[250px] pr-4">
                                            @if(count($upcomingEvents['rental_gives']) > 0)
                                                @foreach($upcomingEvents['rental_gives'] as $rental)
                                                    <x-upcoming-event-item 
                                                        :title="$rental->advertisement->title"
                                                        :date="$rental->start_date"
                                                        :otheruser="$rental->user"
                                                        :otheruser_name="'to ' . $rental->user->name"
                                                        :days-until="now()->startOfDay()->diffInDays($rental->start_date->startOfDay())"
                                                    />
                                                @endforeach
                                            @else
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('No items to give') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Rental Receives -->
                                    <div class="p-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Items to Receive') }}</div>
                                        <div class="overflow-y-auto max-h-[250px] pr-4">
                                            @if(count($upcomingEvents['rental_receives']) > 0)
                                                @foreach($upcomingEvents['rental_receives'] as $rental)
                                                    <x-upcoming-event-item 
                                                        :title="$rental->advertisement->title"
                                                        :date="$rental->end_date"
                                                        :otheruser="$rental->user"
                                                        :otheruser_name="'from ' . $rental->user->name"
                                                        :days-until="now()->startOfDay()->diffInDays($rental->end_date->startOfDay())"
                                                    />
                                                @endforeach
                                            @else
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('No items to receive') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Ending Auctions -->
                                    <div class="p-4">
                                        <div class="flex items-center justify-between pr-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Ending Auctions') }}</div>
                                            <a href="{{ route('auctions.calendar') }}" class="text-blue-500 dark:text-blue-400">{{ __('View Calendar') }}</a>
                                        </div>
                                        <div class="overflow-y-auto max-h-[250px] pr-4">
                                            @if(count($upcomingEvents['ending_auctions']) > 0)
                                                @foreach($upcomingEvents['ending_auctions'] as $auction)
                                                    <x-upcoming-event-item 
                                                        :title="$auction->title"
                                                        :date="$auction->auction_end_date"
                                                        :days-until="now()->startOfDay()->diffInDays($auction->auction_end_date->startOfDay())"
                                                    />
                                                @endforeach
                                            @else
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('No ending auctions') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                        <!-- Business Stats -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium">{{ __('Business Statistics') }}</h3>
                                @if(Auth::user()->isBusiness() || Auth::user()->isAdmin())
                                    <a href="{{ route('business.settings') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ __('Business Settings') }}
                                    </a>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="bg-white p-4 rounded-lg border">
                                    <div class="text-sm font-medium text-gray-500">{{ __('Total Revenue') }}</div>
                                    <div class="mt-1 text-2xl font-semibold">€0.00</div>
                                </div>
                                <div class="bg-white p-4 rounded-lg border">
                                    <div class="text-sm font-medium text-gray-500">{{ __('Active Contracts') }}</div>
                                    <div class="mt-1 text-2xl font-semibold">0</div>
                                </div>
                                <div class="bg-white p-4 rounded-lg border">
                                    <div class="text-sm font-medium text-gray-500">{{ __('Total Orders') }}</div>
                                    <div class="mt-1 text-2xl font-semibold">0</div>
                                </div>
                                <div class="bg-white p-4 rounded-lg border">
                                    <div class="text-sm font-medium text-gray-500">{{ __('Customer Rating') }}</div>
                                    <div class="mt-1 text-2xl font-semibold">-</div>
                                </div>
                            </div>
                        </div>
        
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
