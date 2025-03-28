@props([
    'title',
    'date',
    'daysUntil',
    'otheruser' => null,
    'otheruser_name' => null,
])

<div class="px-3 py-1 mt-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150 border border-gray-100 dark:border-gray-700">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="w-2 h-2 rounded-full {{ $daysUntil <= 2 ? 'bg-red-400' : 'bg-blue-400' }}"></div>
            </div>
            <div class="flex flex-col">
                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $title }}
                </h4>
                <div class="text-xs mt-0.5">
                    @if($otheruser)
                        <a href="{{ route('users.show', $otheruser) }}" class="text-blue-500 dark:text-blue-400">{{ $otheruser_name }}</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex flex-col items-end">
            <time datetime="{{ $date->format('Y-m-d') }}" class="text-sm text-gray-500 dark:text-gray-400">
                {{ $date->format('D, M j') }}
            </time>
            @php
                $label = match(true) {
                    $daysUntil == 0 => '<span class="font-medium text-red-500 dark:text-red-400">' . __('Today') . '</span>',
                    $daysUntil == 1 => '<span class="font-medium text-orange-500 dark:text-orange-400">' . __('Tomorrow') . '</span>',
                    $daysUntil <= 3 => '<span class="font-medium text-yellow-500 dark:text-yellow-400">' . __(':count days', ['count' => $daysUntil]) . '</span>',
                    default => '<span class="text-gray-500 dark:text-gray-400">' . __(':count days', ['count' => $daysUntil]) . '</span>'
                };
            @endphp
            <div class="text-xs mt-0.5">{!! $label !!}</div>
        </div>
    </div>
</div> 