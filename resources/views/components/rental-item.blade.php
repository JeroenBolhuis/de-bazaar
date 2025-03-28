@props(['rental', 'type' => 'rented']) {{-- type can be 'rented' or 'rented-out' --}}

<div class="p-6 flex flex-col sm:flex-row justify-between gap-6 group">
    <div class="flex-grow min-w-0">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <h4 class="font-medium text-gray-900 dark:text-gray-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">
                    {{ $rental->advertisement->title }}
                </h4>
                <div class="mt-2 flex flex-col xs:flex-row xs:items-center gap-3">
                    <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $type === 'rented' ? 'Owner:' : 'Renter:' }} {{ $type === 'rented' ? $rental->advertisement->user->name : $rental->user->name }}
                    </span>
                </div>
                <div class="mt-3 flex flex-col xs:flex-row xs:items-center gap-3">
                    <div class="inline-flex items-center gap-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-full px-4 py-1.5">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">{{ $rental->start_date->format('M d') }}</span>
                        <svg class="w-4 h-4 text-indigo-400 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">{{ $rental->end_date->format('M d') }}</span>
                        <span class="text-sm text-indigo-600 dark:text-indigo-400">({{ $rental->start_date->diffInDays($rental->end_date) + 1 }} days)</span>
                    </div>
                    @php
                        $status = now()->between($rental->start_date, $rental->end_date) ? 'Active' : 
                                (now()->lt($rental->start_date) ? 'Upcoming' : 'Completed');
                        $statusColors = [
                            'Active' => 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-800',
                            'Upcoming' => 'text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800',
                            'Completed' => 'text-gray-700 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/30 border-gray-200 dark:border-gray-800'
                        ][$status];
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium border {{ $statusColors }}">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($status === 'Active')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728"/>
                            @elseif($status === 'Upcoming')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            @endif
                        </svg>
                        {{ $status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center">
        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-transform duration-200">
            €{{ number_format($rental->advertisement->price, 2) }}
        </span>
    </div>
</div> 