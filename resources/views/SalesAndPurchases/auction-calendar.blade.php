<x-app-layout>
    <div class="py-12">
        <x-base-calendar 
            :calendar="$calendar" 
            :month="$month" 
            :year="$year" 
            :previousMonth="$previousMonth" 
            :previousYear="$previousYear" 
            :nextMonth="$nextMonth" 
            :nextYear="$nextYear" 
            type="auction"
        />
    </div>
</x-app-layout> 