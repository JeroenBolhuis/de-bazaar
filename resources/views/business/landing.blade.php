<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Header -->
        <header class="relative bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900" style="color: {{ $business->theme_settings['primary_color'] ?? '#3B82F6' }}">
                            {{ $business->name }}
                        </h1>
                        <p class="mt-2 text-lg text-gray-600">{{ __('Welcome to our business') }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Add any header actions here -->
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="space-y-16">
                @foreach($business->components as $component)
                    @switch($component->type)
                        @case('intro_text')
                            <section class="bg-white rounded-2xl shadow-sm p-8">
                                <h2 class="text-3xl font-semibold mb-6" style="color: {{ $business->theme_settings['secondary_color'] ?? '#10B981' }}">
                                    {{ __('About Us') }}
                                </h2>
                                <div class="prose prose-lg max-w-none text-gray-600">
                                    {{ $component->content }}
                                </div>
                            </section>
                            @break

                        @case('image')
                            <section class="relative max-w-lg mx-auto">
                                @if($component->image_path)
                                    <div class="aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden shadow-lg">
                                        <img src="{{ Storage::url($component->image_path) }}" 
                                             alt="Business Image" 
                                             class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">
                                    </div>
                                @endif
                            </section>
                            @break

                        @case('text_with_image')
                            <section class="bg-white rounded-2xl shadow-sm p-8">
                                <div class="grid md:grid-cols-2 gap-8 items-center">
                                    <div class="prose prose-lg max-w-none text-gray-600">
                                        {{ $component->content }}
                                    </div>
                                    @if($component->image_path)
                                        <div class="relative">
                                            <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden shadow-lg">
                                                <img src="{{ Storage::url($component->image_path) }}" 
                                                     alt="Component Image" 
                                                     class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </section>
                            @break

                        @case('featured_ads')
                            <section class="bg-white rounded-2xl shadow-sm p-8">
                                @php
                                    $adTypes = $component->settings['ad_types'] ?? ['listing'];
                                @endphp
                                
                                @foreach($adTypes as $type)
                                    <div class="mb-12 last:mb-0">
                                        <h2 class="text-3xl font-semibold mb-8" style="color: {{ $business->theme_settings['secondary_color'] ?? '#10B981' }}">
                                            @switch($type)
                                                @case('listing')
                                                    {{ __('Featured Products') }}
                                                    @break
                                                @case('rental')
                                                    {{ __('Featured Rentals') }}
                                                    @break
                                                @case('auction')
                                                    {{ __('Featured Auctions') }}
                                                    @break
                                            @endswitch
                                        </h2>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                            @php
                                                $typeListings = $listings->where('type', $type)->take(3);
                                            @endphp
                                            
                                            @forelse($typeListings as $listing)
                                                <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                                                    <div class="aspect-w-16 aspect-h-9">
                                                        @if($listing->image)
                                                            <img src="{{ Storage::url($listing->image) }}" 
                                                                 alt="{{ $listing->title }}" 
                                                                 class="w-full h-full object-cover group-hover:opacity-90 transition-opacity duration-300">
                                                        @else
                                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                                <span class="text-gray-400">{{ __('No image') }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="p-6">
                                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $listing->title }}</h3>
                                                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $listing->description }}</p>
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-2xl font-bold" style="color: {{ $business->theme_settings['primary_color'] ?? '#3B82F6' }}">
                                                                @if($type === 'auction')
                                                                    {{ __('Current Bid:') }} €{{ number_format($listing->highestBidOrPrice->amount ?? $listing->price, 2) }}
                                                                @elseif($type === 'rental')
                                                                    {{ __('From') }} €{{ number_format($listing->price, 2) }}/{{ __('day') }}
                                                                @else
                                                                    €{{ number_format($listing->price, 2) }}
                                                                @endif
                                                            </span>
                                                            <a href="{{ route('advertisements.show', $listing) }}" 
                                                               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors duration-300"
                                                               style="background-color: {{ $business->theme_settings['primary_color'] ?? '#3B82F6' }}; hover:opacity-90">
                                                                {{ __('View Details') }}
                                                            </a>
                                                        </div>
                                                        @if($type === 'auction' && $listing->auction_end_date)
                                                            <div class="mt-4 text-sm text-gray-500">
                                                                {{ __('Ends') }}: {{ $listing->auction_end_date->format('M d, Y H:i') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-span-full text-center py-12">
                                                    <p class="text-gray-500 text-lg">
                                                        @switch($type)
                                                            @case('listing')
                                                                {{ __('No featured products available.') }}
                                                                @break
                                                            @case('rental')
                                                                {{ __('No featured rentals available.') }}
                                                                @break
                                                            @case('auction')
                                                                {{ __('No featured auctions available.') }}
                                                                @break
                                                        @endswitch
                                                    </p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </section>
                            @break
                    @endswitch
                @endforeach

                <!-- Business Information -->
                <section class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-3xl font-semibold mb-8" style="color: {{ $business->theme_settings['secondary_color'] ?? '#10B981' }}">
                        {{ __('Business Information') }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-semibold mb-4">{{ __('Contact Details') }}</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('KVK Number') }}</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ $business->kvk_number }}</dd>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('VAT Number') }}</dt>
                                    <dd class="mt-1 text-lg text-gray-900">{{ $business->vat_number }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-4">{{ __('Business Hours') }}</h3>
                            <!-- Add business hours if available -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-600">{{ __('Contact us for business hours') }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- All Listings -->
                <section class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-3xl font-semibold mb-8" style="color: {{ $business->theme_settings['secondary_color'] ?? '#10B981' }}">
                        {{ __('All Products') }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse($listings as $listing)
                            <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                                <div class="aspect-w-16 aspect-h-9">
                                    @if($listing->image)
                                        <img src="{{ Storage::url($listing->image) }}" 
                                             alt="{{ $listing->title }}" 
                                             class="w-full h-full object-cover group-hover:opacity-90 transition-opacity duration-300">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-400">{{ __('No image') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $listing->title }}</h3>
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $listing->description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-bold" style="color: {{ $business->theme_settings['primary_color'] ?? '#3B82F6' }}">
                                            €{{ number_format($listing->price, 2) }}
                                        </span>
                                        <a href="{{ route('advertisements.show', $listing) }}" 
                                           class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors duration-300"
                                           style="background-color: {{ $business->theme_settings['primary_color'] ?? '#3B82F6' }}; hover:opacity-90">
                                            {{ __('View Details') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500 text-lg">{{ __('No products available at the moment.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center text-gray-500">
                    <p>&copy; {{ date('Y') }} {{ $business->name }}. {{ __('All rights reserved.') }}</p>
                </div>
            </div>
        </footer>
    </div>
</x-app-layout>
