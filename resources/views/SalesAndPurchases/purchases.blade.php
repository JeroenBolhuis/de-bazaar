<x-app-layout>
    <div class="py-12">
        <x-purchase-list 
            :sales="$purchases" 
            :rentals="$rentals" 
            type="purchased"
            :activeTab="$activeTab"
            :sortBy="$sortBy"
            :sortDirection="$sortDirection"
            :startDate="$startDate"
            :endDate="$endDate"
        />
    </div>
</x-app-layout>
