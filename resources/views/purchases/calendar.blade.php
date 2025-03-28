<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-6">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Rentals Calendar') }}</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('View your rentals in a calendar format') }}</p>
                    </div>
                    <a href="{{ route('purchases.index', ['active_tab' => 'rentals']) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Calendar Navigation -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="p-2 flex items-center justify-between">
                    <a href="{{ route('rentals.calendar', ['month' => $previousMonth, 'year' => $previousYear]) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ \Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}
                    </h3>
                    <a href="{{ route('rentals.calendar', ['month' => $nextMonth, 'year' => $nextYear]) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <!-- Calendar Header -->
                <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                    @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
                        <div class="p-2 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $dayName }}
                        </div>
                    @endforeach
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700">
                    @php
                        $currentWeekRentals = [];
                        $weekStartDate = null;
                    @endphp

                    @foreach($calendar as $date => $rentals)
                        @php
                            $isToday = $date == now()->format('Y-m-d');
                            $isCurrentMonth = \Carbon\Carbon::parse($date)->month == $month;
                            $currentDate = \Carbon\Carbon::parse($date);
                            
                            // Start new week on Monday
                            if ($currentDate->dayOfWeek === 1) {
                                $weekStartDate = $currentDate->copy();
                                $currentWeekRentals = [];

                                // Get all rentals for the week and sort them by start date
                                $weekRentals = collect();
                                for ($i = 0; $i < 7; $i++) {
                                    $dayDate = $weekStartDate->copy()->addDays($i)->format('Y-m-d');
                                    if (isset($calendar[$dayDate])) {
                                        $weekRentals = $weekRentals->concat($calendar[$dayDate]);
                                    }
                                }
                                $weekRentals = $weekRentals->unique('id')->sortBy(function($rental) {
                                    return $rental->start_date->format('Y-m-d');
                                });

                                // Smart row assignment
                                $rowTracks = []; // Keep track of which rows are occupied and until when
                                $weekRentals->each(function($rental) use (&$currentWeekRentals, &$rowTracks) {
                                    $rentalStart = $rental->start_date->format('Y-m-d');
                                    $rentalEnd = $rental->end_date->format('Y-m-d');
                                    
                                    // Find the first available row
                                    $rowPosition = 0;
                                    while (true) {
                                        if (!isset($rowTracks[$rowPosition])) {
                                            // Row is completely free
                                            $rowTracks[$rowPosition] = $rentalEnd;
                                            break;
                                        }
                                        
                                        if ($rowTracks[$rowPosition] < $rentalStart) {
                                            // This row is free for our date range
                                            $rowTracks[$rowPosition] = $rentalEnd;
                                            break;
                                        }
                                        
                                        $rowPosition++;
                                    }
                                    
                                    $currentWeekRentals[$rental->id] = $rowPosition;
                                });
                            }
                        @endphp
                        <div class="min-h-[110px] bg-white dark:bg-gray-800 {{ $isCurrentMonth ? '' : 'bg-gray-50 dark:bg-gray-900' }} relative">
                            <div class="flex items-center justify-between p-2 pb-0">
                                <span class="text-sm w-5 h-5 {{ $isToday ? 'bg-indigo-600 text-white rounded-full flex items-center justify-center' : ($isCurrentMonth ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-600') }}">
                                    {{ $currentDate->format('j') }}
                                </span>
                            </div>
                            <div class="grid gap-1.5 relative pt-1" style="grid-template-rows: repeat({{ max(1, count($currentWeekRentals)) }}, minmax(24px, 24px))">
                                @foreach($rentals as $rental)
                                    @php
                                        $isStartDate = $currentDate->isSameDay($rental->start_date);
                                        $isEndDate = $currentDate->isSameDay($rental->end_date);
                                        $isMiddleDate = $currentDate->between($rental->start_date, $rental->end_date) && !$isStartDate && !$isEndDate;
                                        
                                        $borderClasses = '';
                                        if ($isStartDate) {
                                            $borderClasses = 'rounded-l-md border-l-2 border-t-2 border-b-2 ml-3';
                                        } elseif ($isEndDate) {
                                            $borderClasses = 'rounded-r-md border-r-2 border-t-2 border-b-2 mr-3 pl-3';
                                        } elseif ($isMiddleDate) {
                                            $borderClasses = 'border-t-2 border-b-2 pl-3';
                                        }

                                        $rowPosition = $currentWeekRentals[$rental->id] ?? 0;
                                    @endphp
                                    
                                    <div class="relative group h-6" style="grid-row: {{ $rowPosition + 1 }}">
                                        <div class="text-xs p-1 h-full {{ $borderClasses }} border-indigo-400 dark:border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 relative">
                                            <div class="truncate">{{ Str::limit($rental->advertisement->title, 20) }}</div>
                                            
                                            @if($isStartDate)
                                                <div class="absolute -top-2 -left-3 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400 text-[10px] px-1 rounded-full transform -rotate-12 shadow-sm border border-green-200 dark:border-green-800">
                                                    Pickup
                                                </div>
                                            @endif
                                            @if($isEndDate)
                                                <div class="absolute -top-2 -right-3 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400 text-[10px] px-1 rounded-full transform rotate-12 shadow-sm border border-red-200 dark:border-red-800">
                                                    Return
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Tooltip -->
                                        <div class="absolute z-10 w-48 p-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 invisible group-hover:visible left-1/2 transform -translate-x-1/2 mt-1">
                                            <div class="text-xs font-medium text-gray-900 dark:text-gray-100">
                                                {{ $rental->advertisement->title }}
                                            </div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                <div class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    Pickup: {{ $rental->start_date->format('M j, Y') }}
                                                </div>
                                                <div class="flex items-center mt-1">
                                                    <svg class="w-3 h-3 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    Return: {{ $rental->end_date->format('M j, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 