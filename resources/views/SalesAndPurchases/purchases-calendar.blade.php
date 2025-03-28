<x-app-layout>
    <div class="py-12">
        <x-rental-calendar 
            :calendar="$calendar" 
            :month="$month" 
            :year="$year" 
            :previousMonth="$previousMonth" 
            :previousYear="$previousYear" 
            :nextMonth="$nextMonth" 
            :nextYear="$nextYear" 
            type="rented"
        />
    </div>
</x-app-layout> 