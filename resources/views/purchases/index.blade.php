<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('My Purchases') }}</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Overview of your bought products') }}</p>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
                <form method="GET" action="{{ route('purchases.index') }}"
                      class="flex flex-col md:flex-row items-center gap-4">

                    <!-- Date Range -->
                    <div class="flex items-center gap-2">
                        <label for="start_date" class="text-sm text-gray-700 dark:text-gray-300">From:</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm">
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="end_date" class="text-sm text-gray-700 dark:text-gray-300">To:</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm">
                    </div>

                    <!-- Sort -->
                    <div>
                        <select name="sort"
                                class="rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm">
                            <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Newest
                                First
                            </option>
                            <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Oldest
                                First
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Purchases List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                @forelse($purchases as $purchase)
                    <div class="border-b border-gray-200 dark:border-gray-700 py-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ $purchase->advertisement->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Purchased
                                on: {{ $purchase->purchase_date->format('d-m-Y') }}</p>
                        </div>
                        <span class="text-indigo-600 font-bold">€{{ $purchase->advertisement->price }}</span>
                    </div>
                @empty
                    <p class="text-gray-600 dark:text-gray-300">No purchases found.</p>
                @endforelse

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $purchases->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
