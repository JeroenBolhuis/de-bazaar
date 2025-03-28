<x-app-layout>
    <div class="py-12">
        <x-purchase-list 
            :sales="$sales" 
            :rentals="$rentals" 
            type="sold"
            :activeTab="$activeTab"
            :sortBy="$sortBy"
            :sortDirection="$sortDirection"
            :startDate="$startDate"
            :endDate="$endDate"
        />
    </div>
</x-app-layout> 