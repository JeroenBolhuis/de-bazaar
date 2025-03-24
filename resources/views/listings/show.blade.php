<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $advertisement->title }}</h1>

            <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $advertisement->description }}</p>

            <div class="flex justify-between items-center mb-4">
                <span class="text-xl font-semibold text-indigo-600">€{{ $advertisement->price }}</span>
                <span class="text-gray-500 dark:text-gray-400">Posted by: {{ $advertisement->user->name }}</span>
            </div>

            @if ($advertisement->location)
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Location: {{ $advertisement->location }}</p>
            @endif

            @auth
                <form action="{{ route('advertisements.purchase', $advertisement->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded">
                        Buy Now
                    </button>
                </form>
            @endauth

        </div>
    </div>
</x-app-layout>
