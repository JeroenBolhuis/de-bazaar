<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Page Component') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('business.components.store', $business) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="type" :value="__('Component Type')" />
                        <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded" required>
                            <option value="intro_text">Intro Text</option>
                            <option value="image">Image</option>
                            <option value="featured_ads">Featured Ads</option>
                        </select>
                    </div>

                    <div class="mb-4" id="content-wrapper">
                        <x-input-label for="content" :value="__('Content (for Intro Text)')" />
                        <textarea name="content" id="content" rows="4" class="mt-1 block w-full border-gray-300 rounded"></textarea>
                    </div>

                    <div class="mb-4 hidden" id="image-wrapper">
                        <x-input-label for="image" :value="__('Image Upload')" />
                        <input type="file" name="image" id="image" class="mt-1 block w-full">
                    </div>

                    <x-primary-button class="mt-4">
                        {{ __('Add Component') }}
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const typeSelect = document.getElementById('type');
            const imageWrapper = document.getElementById('image-wrapper');
            const contentWrapper = document.getElementById('content-wrapper');

            typeSelect.addEventListener('change', function () {
                imageWrapper.classList.toggle('hidden', this.value !== 'image');
                contentWrapper.classList.toggle('hidden', this.value !== 'intro_text');
            });

            typeSelect.dispatchEvent(new Event('change'));
        </script>
    @endpush
</x-app-layout>
