@props(['component'])

<div class="image-component">
    <div class="container mx-auto px-4">
        <div class="relative max-w-2xl mx-auto">
            <img src="{{ $component->image ? asset('storage/' . $component->image) : 'https://placehold.co/600x400' }}" 
                 alt="Business Image" 
                 class="w-full h-auto rounded-lg shadow-lg">
            <div class="mt-4 text-center text-gray-600">
                {{ $component->title ?? 'Business Image' }}
            </div>
        </div>
    </div>
</div>
