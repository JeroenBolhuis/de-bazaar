<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Edit Button for Business Owner -->
        @auth
            @if(Auth::user()->business && Auth::user()->business->id === $business->id)
                <div class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-end">
                            <a href="{{ route('business.components.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('Edit Landing Page') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Hero Section -->
        <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-indigo-800 dark:from-indigo-900 dark:to-indigo-950"></div>
            <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        {{ $business->name }}
                    </h1>
                    <p class="mt-6 text-xl text-indigo-100 max-w-3xl mx-auto">
                        {{ __('Welcome to our business page. Explore our products and services.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- About Us -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('About Us') }}</h2>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ __('We are a trusted business providing quality products and services to our customers.') }}
                </p>
            </div>

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

            <!-- Business Components -->
            <div class="space-y-8">
                @foreach($business->components as $component)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        @if($component->pivot->title)
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $component->pivot->title }}</h3>
                        @endif
                        
                        @php
                            $componentData = [
                                'title' => $component->pivot->title,
                                'content' => $component->pivot->content,
                                'image' => $component->pivot->image,
                                'advertisements' => $business->advertisements
                            ];
                        @endphp

                        @include('business.components.' . $component->type, ['component' => (object)$componentData])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout> 