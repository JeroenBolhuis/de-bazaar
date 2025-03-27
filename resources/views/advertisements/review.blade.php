<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('advertisements.show', $advertisement) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Advertisement
                    </a>
                </div>

                <h1 class="text-2xl font-bold mb-6">Review for {{ $advertisement->title }}</h1>

                <form action="{{ route('advertisements.review.store', $advertisement) }}" method="POST" x-data="{ rating: 0 }">
                    @csrf

                    <!-- Star Rating -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Rating</label>
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button 
                                    type="button"
                                    @click="rating = {{ $i }}"
                                    class="focus:outline-none"
                                    :class="{ 'text-yellow-400': rating >= {{ $i }}, 'text-gray-300 dark:text-gray-600': rating < {{ $i }} }"
                                >
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" x-model="rating" required>
                        @error('rating')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="mb-6">
                        <label for="comment" class="block text-sm font-medium mb-2">Comment (Optional)</label>
                        <textarea
                            id="comment"
                            name="comment"
                            rows="4"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Share your experience..."
                        >{{ old('comment') }}</textarea>
                        @error('comment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600"
                        >
                            Submit Review
                        </button>
                        <a
                            href="{{ route('advertisements.show', $advertisement) }}"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 