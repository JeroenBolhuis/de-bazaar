<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </head>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100 relative">
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('advertisements.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Advertisements
                    </a>
                </div>

                <!-- Main Advertisement Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Image Section -->
                    <div class="">
                        @if($advertisement->image)
                            <img src="{{ Storage::url($advertisement->image) }}" alt="{{ $advertisement->title }}" class="w-full h-96 object-cover rounded-lg">
                        @else
                            <div class="w-full h-96 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400 dark:text-gray-500">No image available</span>
                            </div>
                        @endif
                    </div>

                    <!-- Details Section -->
                    <div class="flex flex-col gap-4">
                        <h1 class="text-3xl font-bold dark:text-white">{{ $advertisement->title }}</h1>
                        <p class="text-gray-600 dark:text-gray-300">{{ $advertisement->description }}</p>
                        
                        <!-- Price Section -->
                        <div class="text-2xl font-semibold dark:text-white">
                            €{{ number_format($advertisement->highestBidOrPrice->amount, 2) }}
                        </div>

                        <!-- Seller Info -->
                        <div class="border-t dark:border-gray-700 pt-4 flex items-center justify-between">
                            <a href="{{ route('users.show', $advertisement->user) }}" class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                <span class="">Posted by {{ $advertisement->user->name }}</span>                        
                                <span class="">({{ $advertisement->user->reviews()->count() }} reviews)</span>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $advertisement->user->reviews()->average('rating') ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </a>
                            @if(!(auth()->check() && auth()->id() === $advertisement->user_id))
                                <a href="{{ route('users.review', $advertisement->user) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                    Add Review
                                </a>
                            @endif
                        </div>

                        <!-- Type-specific Actions -->
                        @if($advertisement->type === 'listing' && !(auth()->check() && auth()->id() === $advertisement->user_id))
                            <div class="mt-6">
                                <form action="{{ route('advertisements.purchase', $advertisement->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                        Purchase Now
                                    </button>
                                </form>
                            </div>
                        @elseif($advertisement->type === 'rental')
                            <div class="mt-6 space-y-4">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h3 class="font-semibold dark:text-white">Rental Details</h3>
                                    <p class="dark:text-gray-300">Condition: {{ $advertisement->condition }}%</p>
                                    <p class="dark:text-gray-300">Wear per day: {{ $advertisement->wear_per_day }}%</p>
                                </div>
                                @if(!(auth()->check() && auth()->id() === $advertisement->user_id))
                                    <div class="mt-4">
                                        <h3 class="text-lg font-semibold mb-2">Rent this item</h3>
                                        <form action="{{ route('advertisements.rent', $advertisement) }}" method="POST" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label for="date_range" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Rental Period</label>
                                                <input type="text" id="date_range" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400" placeholder="Select dates..." readonly>
                                                <input type="hidden" name="start_date" id="start_date">
                                                <input type="hidden" name="end_date" id="end_date">
                                            </div>
                                            <div class="text-sm space-y-2">
                                                <div id="total-cost" class="hidden">
                                                    <p class="text-gray-600 dark:text-gray-400">
                                                        Rental period: <span id="rental-days">0</span> days
                                                    </p>
                                                    <p class="font-semibold text-gray-700 dark:text-gray-300">
                                                        Total cost: €<span id="total-cost-amount">0.00</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                                Rent Now
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @elseif($advertisement->type === 'auction')
                            <div class="mt-6 space-y-4">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h3 class="font-semibold dark:text-white">Auction Details</h3>
                                    <p class="dark:text-gray-300">Start Date: {{ $advertisement->auction_start_date->format('d-m-Y H:i') }}</p>
                                    <p class="dark:text-gray-300">End Date: {{ $advertisement->auction_end_date->format('d-m-Y H:i') }}</p>
                                    @if($advertisement->bids->count() > 0)
                                        <p class="dark:text-gray-300">Current Highest Bid: €{{ number_format($advertisement->bids->max('amount'), 2) }}</p>
                                    @endif
                                </div>
                                @if($advertisement->isAuctionActive() && !(auth()->check() && auth()->id() === $advertisement->user_id))
                                    <form action="{{ route('advertisements.bid', $advertisement->id) }}" method="POST">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Your Bid Amount</label>
                                                <input type="number" name="amount" step="0.01" min="{{ $advertisement->bids->max('amount') ?? $advertisement->price }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400">
                                            </div>
                                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                                Place Bid
                                            </button>
                                        </div>
                                    </form>
                                @elseif(!$advertisement->isAuctionActive())
                                    <div class="text-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                        @if(now()->lt($advertisement->auction_start_date))
                                            <p class="text-gray-600 dark:text-gray-400">Auction hasn't started yet</p>
                                        @else
                                            <p class="text-gray-600 dark:text-gray-400">Auction has ended</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Favorite Button -->
                        @auth
                            <div
                                x-data="{ favorited: {{ auth()->user()->favorites->contains($advertisement) ? 'true' : 'false' }}, loading: false }"
                                class="absolute top-6 right-6"
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
                                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400 stroke-current fill-none transition-all duration-300 ease-in-out" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="mt-8 border-t dark:border-gray-700 pt-8 flex flex-col gap-4">
                    <div class="flex justify-between">
                        <h2 class="mb-4 dark:text-white flex items-center">
                            <span class="text-2xl font-bold mr-2">Reviews</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $advertisement->reviews()->count() }} reviews)</span>
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $advertisement->reviews()->average('rating') ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </h2>
                        @if(!(auth()->check() && auth()->id() === $advertisement->user_id))
                            <div>
                                <a href="{{ route('advertisements.review', $advertisement) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                    Add Review
                                </a>
                            </div>
                        @endif
                    </div>
                    @if($advertisement->reviews->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($advertisement->reviews->take(12) as $review)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold dark:text-white">{{ $review->reviewer->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mt-2 dark:text-gray-300">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No reviews yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            // Add custom styles for dark mode and range highlighting
            const darkModeStyles = `
                .flatpickr-calendar.dark {
                    background: #1f2937;
                    border-color: #374151;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
                }
                .flatpickr-calendar.dark .flatpickr-months,
                .flatpickr-calendar.dark .flatpickr-weekdays {
                    background: #1f2937;
                }
                .flatpickr-calendar.dark .flatpickr-month {
                    color: #fff;
                }
                .flatpickr-calendar.dark .flatpickr-weekday {
                    color: #9ca3af;
                }
                .flatpickr-calendar.dark .flatpickr-day {
                    color: #fff;
                }
                .flatpickr-calendar.dark .flatpickr-day.disabled {
                    color: #6b7280;
                }
                .flatpickr-calendar.dark .flatpickr-day.selected,
                .flatpickr-calendar.dark .flatpickr-day.startRange,
                .flatpickr-calendar.dark .flatpickr-day.endRange {
                    background: #4f46e5;
                    border-color: #4f46e5;
                    color: #fff;
                }
                .flatpickr-calendar.dark .flatpickr-day.inRange {
                    background: #312e81;
                    border-color: #312e81;
                    color: #fff;
                }
                .flatpickr-calendar.dark .flatpickr-day:hover {
                    background: #374151;
                }
                .flatpickr-calendar.dark .flatpickr-current-month .flatpickr-monthDropdown-months,
                .flatpickr-calendar.dark .flatpickr-current-month input.cur-year {
                    color: #fff;
                    background: #1f2937;
                }
                .flatpickr-calendar.dark .flatpickr-months .flatpickr-prev-month,
                .flatpickr-calendar.dark .flatpickr-months .flatpickr-next-month {
                    color: #fff;
                    fill: #fff;
                }
            `;

            // Add styles to document
            const styleSheet = document.createElement("style");
            styleSheet.innerText = darkModeStyles;
            document.head.appendChild(styleSheet);

            const pricePerDay = {{ $advertisement->wear_per_day }};
            const totalCostDiv = document.getElementById('total-cost');
            const rentalDaysSpan = document.getElementById('rental-days');
            const totalCostSpan = document.getElementById('total-cost-amount');

            // Function to calculate days between two dates
            function calculateDays(start, end) {
                const diffTime = Math.abs(end - start);
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            }

            // Function to format number as currency
            function formatCurrency(number) {
                return number.toFixed(2);
            }

            // Fetch blocked dates
            fetch('{{ route('advertisements.blocked-dates', $advertisement) }}')
                .then(response => response.json())
                .then(blockedDates => {
                    // Initialize date range picker
                    const dateRangePicker = flatpickr("#date_range", {
                        mode: "range",
                        minDate: "today",
                        disable: blockedDates,
                        dateFormat: "Y-m-d",
                        showMonths: 2,
                        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                        onChange: function(selectedDates) {
                            if (selectedDates.length === 2) {
                                const days = calculateDays(selectedDates[0], selectedDates[1]);
                                const totalCost = days * pricePerDay;
                                
                                document.getElementById('start_date').value = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                                document.getElementById('end_date').value = flatpickr.formatDate(selectedDates[1], "Y-m-d");
                                
                                rentalDaysSpan.textContent = days;
                                totalCostSpan.textContent = formatCurrency(totalCost);
                                totalCostDiv.classList.remove('hidden');
                            } else {
                                totalCostDiv.classList.add('hidden');
                            }
                        }
                    });

                    // Update theme when system preference changes
                    if (window.matchMedia) {
                        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                            dateRangePicker.set('theme', e.matches ? 'dark' : 'light');
                        });
                    }
                });
        });
    </script>
</x-app-layout>