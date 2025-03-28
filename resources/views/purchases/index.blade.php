<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </head>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('My Orders') }}</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Overview of your purchases and rentals') }}</p>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
                <form method="GET" action="{{ route('purchases.index') }}" 
                      class="flex flex-col md:flex-row items-start gap-6">
                    <div class="flex flex-col sm:flex-row gap-4 flex-grow w-full">
                        <div class="w-full sm:w-2/3">
                            <div class="flex flex-col gap-1.5 w-full">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Date Range</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="date_range"
                                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 pl-10"
                                        placeholder="Select date range..."
                                        readonly
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                                <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>

                        <div class="w-full sm:w-1/3">
                            <div class="flex flex-col gap-1.5 w-full">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Sort By</label>
                                <select name="sort"
                                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Newest First</option>
                                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-auto self-end">
                        <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabs -->
            <div class="mb-6">
                <nav class="flex space-x-4" aria-label="Tabs">
                    <button onclick="showTab('purchases')" 
                            class="tab-button px-4 py-2 text-sm font-medium rounded-md" 
                            data-tab="purchases">
                        Purchases
                    </button>
                    <button onclick="showTab('rentals')" 
                            class="tab-button px-4 py-2 text-sm font-medium rounded-md" 
                            data-tab="rentals">
                        Rentals
                    </button>
                </nav>
            </div>

            <!-- Purchases Tab -->
            <div id="purchases-tab" class="tab-content bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Purchased Items</h3>
                    @forelse($listings as $listing)
                        <div class="border-b border-gray-200 dark:border-gray-700 py-4 flex flex-col sm:flex-row justify-between gap-4">
                            <div class="flex-grow">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $listing->advertisement->title }}</h4>
                                <div class="mt-2 flex flex-col sm:flex-row sm:items-center gap-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Purchased: {{ $listing->purchase_date->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">€{{ number_format($listing->advertisement->price, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No purchases</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You haven't made any purchases yet.</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $listings->withQueryString()->links() }}
                    </div>
                </div>
            </div>

            <!-- Rentals Tab -->
            <div id="rentals-tab" class="tab-content bg-white dark:bg-gray-800 rounded-lg shadow hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Rented Items</h3>
                    @forelse($rentals as $rental)
                        <div class="border-b border-gray-200 dark:border-gray-700 py-4 flex flex-col sm:flex-row justify-between gap-4">
                            <div class="flex-grow">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $rental->advertisement->title }}</h4>
                                <span class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Rented: {{ $rental->created_at->format('M d, Y') }}
                                </span>
                                <div class="mt-3 flex flex-col xs:flex-row xs:items-center gap-4">
                                    <div class="flex items-center gap-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg px-3 py-1.5">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">{{ $rental->start_date->format('M d') }}</span>
                                        <span class="hidden xs:block text-indigo-400 dark:text-indigo-500">→</span>
                                        <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">{{ $rental->end_date->format('M d') }}</span>
                                        <span class="text-sm text-indigo-600 dark:text-indigo-400">({{ $rental->start_date->diffInDays($rental->end_date) + 1 }} days)</span>

                                    </div>
                                    @php
                                        $status = now()->between($rental->start_date, $rental->end_date) ? 'Active' : 
                                                (now()->lt($rental->start_date) ? 'Upcoming' : 'Completed');
                                        $statusColor = [
                                            'Active' => 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30',
                                            'Upcoming' => 'text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30',
                                            'Completed' => 'text-gray-700 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/30'
                                        ][$status];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-sm font-medium {{ $statusColor }}">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">€{{ number_format($rental->advertisement->price, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No rentals</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You haven't rented any items yet.</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $rentals->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching Script
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // Update tab button styles
            document.querySelectorAll('.tab-button').forEach(button => {
                if (button.dataset.tab === tabName) {
                    button.classList.add('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-indigo-200');
                    button.classList.remove('text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                } else {
                    button.classList.remove('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-indigo-200');
                    button.classList.add('text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400', 'dark:hover:text-gray-300');
                }
            });
        }

        // Initialize Flatpickr
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date range picker
            const dateRangePicker = flatpickr("#date_range", {
                mode: "range",
                dateFormat: "d/m/Y",
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                defaultDate: [
                    document.getElementById('start_date').value,
                    document.getElementById('end_date').value
                ].filter(Boolean),
                onChange: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        document.getElementById('start_date').value = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                        document.getElementById('end_date').value = flatpickr.formatDate(selectedDates[1], "Y-m-d");
                    } else {
                        document.getElementById('start_date').value = '';
                        document.getElementById('end_date').value = '';
                    }
                }
            });

            // Show purchases tab by default
            showTab('purchases');
        });
    </script>
</x-app-layout>
