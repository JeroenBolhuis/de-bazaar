<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Listings') }}</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Browse all available items for sale') }}</p>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                <!-- Filters -->
                <div class="w-full md:w-64 flex-none">
                    <!-- Filters blijven ongewijzigd -->
                </div>

                <!-- Listings Grid -->
                <div class="flex-grow">
                    <!-- Sort Options -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6 flex items-center justify-between">
                        <!-- Sorting dropdown blijft ongewijzigd -->
                    </div>

                    <!-- Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($advertisements as $advertisement)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden relative">

                                <!-- Link to Detail Page -->
                                <a href="{{ route('advertisements.show', $advertisement->id) }}"
                                   class="block hover:opacity-90 transition">

                                    <!-- Advertisement Image -->
                                    <div class="aspect-w-3 aspect-h-2">
                                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700"></div>
                                    </div>

                                    <!-- Listing Content -->
                                    <div class="p-4">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $advertisement->title }}</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $advertisement->description }}</p>
                                        <div class="mt-4 flex items-center justify-between">
                                            <span
                                                class="text-lg font-bold text-indigo-600">€{{ $advertisement->price }}</span>
                                            <span
                                                class="text-sm text-gray-500 dark:text-gray-400">{{ $advertisement->location }}</span>
                                        </div>
                                    </div>
                                </a>

                                <!-- Favorite Button -->
                                @auth
                                    <div
                                        x-data="{ favorited: {{ auth()->user()->favorites->contains($advertisement) ? 'true' : 'false' }} }"
                                        class="absolute top-2 right-2"
                                    >
                                        <button
                                            @click.prevent="
                                                fetch('{{ route('advertisements.favorite', $advertisement->id) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Accept': 'application/json',
                                                    },
                                                }).then(() => {
                                                    favorited = !favorited;
                                                });
                                            "
                                            class="focus:outline-none transition-transform transform hover:scale-110"
                                        >
                                            <template x-if="favorited">
                                                <x-heroicon-s-heart class="w-6 h-6 text-red-500"/>
                                            </template>
                                            <template x-if="!favorited">
                                                <x-heroicon-o-heart class="w-6 h-6 text-gray-500"/>
                                            </template>
                                        </button>
                                    </div>
                                @endauth

                            </div>
                        @empty
                            <p class="text-gray-600 dark:text-gray-300">{{ __('No advertisements found.') }}</p>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $advertisements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
