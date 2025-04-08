@props(['business'])

<!-- Contact Information -->
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-8">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Contact Information') }}</h2>
    <div class="space-y-2">
        <p class="text-gray-600 dark:text-gray-300">
            <span class="font-medium">{{ __('Email:') }}</span> {{ $business->users->first()->email }}
        </p>
        <p class="text-gray-600 dark:text-gray-300">
            <span class="font-medium">{{ __('Phone:') }}</span> {{ $business->users->first()->phone }}
        </p>
        <p class="text-gray-600 dark:text-gray-300">
            <span class="font-medium">{{ __('Address:') }}</span> {{ $business->users->first()->address }}, {{ $business->users->first()->postal_code }} {{ $business->users->first()->city }}
        </p>
    </div>
</div>