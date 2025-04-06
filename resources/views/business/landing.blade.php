<x-app-layout>
    <div class="min-h-screen bg-white">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900" style="color: {{ $business->theme_settings['primary_color'] ?? '#3B82F6' }}">
                        {{ $business->name }}
                    </h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            @foreach($business->components as $component)
                @switch($component->type)
                    @case('intro_text')
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold mb-2">{{ __('Introduction') }}</h2>
                            <p class="text-gray-700">{{ $component->content }}</p>
                        </div>
                        @break

                    @case('image')
                        <div class="mb-8 text-center">
                            <img src="{{ Storage::url($component->image_path) }}" alt="Business Image" class="mx-auto max-w-xl rounded shadow">
                        </div>
                        @break

                    @case('featured_ads')
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold mb-4">{{ __('Featured Advertisements') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($listings->take(3) as $listing)
                                    <!-- Same card layout for listings -->
                                    <div class="border rounded-lg overflow-hidden">
                                        <img src="{{ $listing->image_url }}" alt="{{ $listing->title }}" class="w-full h-48 object-cover">
                                        <div class="p-4">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $listing->title }}</h3>
                                            <p class="mt-1 text-sm text-gray-500">{{ $listing->description }}</p>
                                            <a href="{{ route('advertisements.show', $listing) }}" class="inline-block mt-3 px-4 py-2 bg-indigo-600 text-white text-sm rounded">
                                                {{ __('View Details') }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @break
                @endswitch
            @endforeach
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Business Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold mb-4" style="color: {{ $business->theme_settings['secondary_color'] ?? '#10B981' }}">
                            {{ __('About Us') }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Contact Information') }}</h3>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('KVK Number') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $business->kvk_number }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('VAT Number') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $business->vat_number }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Listings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold mb-4" style="color: {{ $business->theme_settings['secondary_color'] ?? '#10B981' }}">
                            {{ __('Our Listings') }}
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($listings as $listing)
                                <div class="border rounded-lg overflow-hidden">
                                    <img src="{{ $listing->image_url }}" alt="{{ $listing->title }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $listing->title }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ $listing->description }}</p>
                                        <div class="mt-4">
                                            <a href="{{ route('advertisements.show', $listing) }}"
                                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white"
                                               style="background-color: {{ $business->theme_settings['primary_color'] ?? '#3B82F6' }}">
                                                {{ __('View Details') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12">
                                    <p class="text-gray-500">{{ __('No listings available at the moment.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
