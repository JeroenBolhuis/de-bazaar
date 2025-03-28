<x-app-layout>
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
                      class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex flex-col sm:flex-row gap-4 flex-grow">
                        <div class="flex items-center gap-3">
                            <label for="start_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">From:</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                   class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-center gap-3">
                            <label for="end_date" class="text-sm font-medium text-gray-700 dark:text-gray-300">To:</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                   class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-center">
                            <select name="sort"
                                    class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Newest First</option>
                                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Oldest First</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
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
                                <div class="mt-2 flex flex-col sm:flex-row sm:items-center gap-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $rental->start_date->format('M d, Y') }} - {{ $rental->end_date->format('M d, Y') }}
                                    </span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Duration: {{ $rental->start_date->diffInDays($rental->end_date) + 1 }} days
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

    <!-- Tab Switching Script -->
    <script>
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

        // Show purchases tab by default
        document.addEventListener('DOMContentLoaded', () => {
            showTab('purchases');
        });
    </script>
</x-app-layout>
