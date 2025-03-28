<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
    <form method="GET" action="{{ request()->url() }}" 
          class="flex flex-col md:flex-row items-start gap-6">
        <input type="hidden" name="active_tab" id="active_tab" value="{{ request('active_tab', 'purchases') }}">
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

            @if($activeTab === 'rentals')
                <div class="w-full sm:w-1/3">
                    <div class="flex flex-col gap-1.5 w-full">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="rental_state"
                                class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all" {{ request('rental_state') == 'all' ? 'selected' : '' }}>All</option>
                            <option value="upcoming" {{ request('rental_state') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="active" {{ request('rental_state') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('rental_state') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>
            @endif

            <div class="w-full sm:w-1/3">
                <div class="flex flex-col gap-1.5 w-full">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Sort By</label>
                    <select name="sort"
                            class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Most Recent</option>
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

<script>
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
});
</script> 