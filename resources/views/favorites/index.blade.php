<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-8">Mijn Favorieten</h2>

            @if ($favorites->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($favorites as $advertisement)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden relative">
                            <!-- Image -->
                            <div class="aspect-w-3 aspect-h-2">
                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700"></div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $advertisement->title }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $advertisement->description }}</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-lg font-bold text-indigo-600">€{{ $advertisement->price }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $advertisement->location }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $favorites->links() }}
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-300">Je hebt nog geen favorieten!</p>
            @endif
        </div>
    </div>
</x-app-layout>
