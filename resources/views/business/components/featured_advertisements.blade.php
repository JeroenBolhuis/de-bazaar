@props(['business'])

<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-8">
    <h2 class="text-2xl font-bold mb-6">Featured Advertisements</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($business->advertisements as $advertisement)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($advertisement->image)
                    <img src="{{ asset('storage/' . $advertisement->image) }}" alt="{{ $advertisement->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">{{ $advertisement->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($advertisement->description, 100) }}</p>
                    <a href="{{ route('advertisements.show', $advertisement) }}" class="text-blue-600 hover:text-blue-800">View Details</a>
                </div>
            </div>
        @endforeach
    </div>
</div>