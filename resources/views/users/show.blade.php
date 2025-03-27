<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100 relative">
                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </a>
                </div>

                <!-- User Profile Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Left Column - User Info -->
                    <div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <div class="mb-4 flex items-start space-x-4">
                                <!-- User Avatar (placeholder) -->
                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                                    <p class="text-gray-500 dark:text-gray-400">Member since {{ $user->created_at->format('F Y') }}</p>
                                </div>
                            </div>

                            <!-- User Stats -->
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Total Reviews</span>
                                    <span class="font-semibold">{{ $user->reviews->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Average Rating</span>
                                    <div class="flex items-center">
                                        <span class="font-semibold mr-2">{{ number_format($user->reviews->avg('rating'), 1) }}</span>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= round($user->reviews->avg('rating')) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Active Listings</span>
                                    <span class="font-semibold">{{ $user->advertisements()->where('is_active', true)->count() }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reviews Section -->
                        <div class="pt-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <h2 class="text-2xl font-bold">Reviews</h2>
                                    @if(auth()->id() !== $user->id)
                                        <a href="{{ route('users.review', $user) }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Write a Review
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="overflow-y-auto max-h-[350px] pr-4">
                                @if($user->reviews->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($user->reviews->sortByDesc('created_at') as $review)
                                            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-600 flex flex-col gap-4">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex items-start space-x-4">
                                                        <!-- User Avatar (placeholder) -->
                                                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="font-semibold dark:text-white">{{ $review->reviewer->name }}</p>
                                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->format('M d, Y') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                                @if($review->comment)
                                                    <p class="text-gray-600 dark:text-gray-300">{{ $review->comment }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <p class="mt-4 text-gray-500 dark:text-gray-400">No reviews yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Content -->
                    <div class="md:col-span-2">
                        <!-- Active Listings -->
                        <div class="">
                            <h2 class="text-xl font-bold mb-4">Latest Active Listings</h2>
                            <a href="{{ route('advertisements.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View All Active Listings</a>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto pr-4 mt-0.5">
                                @forelse($user->advertisements()->where('is_active', true)->latest()->take(6)->get() as $advertisement)
                                    <a href="{{ route('advertisements.show', $advertisement) }}" class="block bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                        <div class="aspect-w-16 aspect-h-9">
                                            @if($advertisement->image)
                                                <img src="{{ Storage::url($advertisement->image) }}" alt="{{ $advertisement->title }}" class="w-full h-48 object-cover">
                                            @else
                                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                    <span class="text-gray-400 dark:text-gray-500">No image</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-semibold mb-2">{{ $advertisement->title }}</h3>
                                            <p class="text-blue-600 dark:text-blue-400">€{{ number_format($advertisement->price, 2) }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-gray-500 dark:text-gray-400 col-span-2">No active listings</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 