<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Edit Button for Business Owner -->
        @auth
            @if(Auth::user()->business && Auth::user()->business->id === $business->id)
                <div class="flex justify-end my-4">
                    <a href="{{ route('business.components.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('Edit Landing Page') }}
                    </a>
                </div>
            @endif
        @endauth

        <!-- Business Components -->
        <div class="space-y-8">
            @foreach($business->components as $component)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    @include('business.components.' . $component->type, ['business' => $business])
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout> 