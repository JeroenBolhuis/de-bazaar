@props(['component'])

<div class="introduction-text py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-6">{{ $component->title ?? 'Welcome' }}</h2>
            <div class="prose prose-lg mx-auto">
                {!! $component->content !!}
            </div>
        </div>
    </div>
</div> 