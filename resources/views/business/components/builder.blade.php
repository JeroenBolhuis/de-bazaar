<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Landing Page Builder') }}
        </h2>
    </x-slot>

    <div class="flex gap-8 p-6">
        <!-- Component Palette -->
        <div class="w-1/3">
            <h3 class="font-bold mb-2">{{ __('Available Components') }}</h3>
            <div class="space-y-2" id="component-palette">
                <div class="draggable bg-white p-3 rounded shadow cursor-move" draggable="true" data-type="intro_text">📝 Intro Text</div>
                <div class="draggable bg-white p-3 rounded shadow cursor-move" draggable="true" data-type="image">🖼️ Image</div>
                <div class="draggable bg-white p-3 rounded shadow cursor-move" draggable="true" data-type="featured_ads">⭐ Featured Ads</div>
            </div>
        </div>

        <!-- Canvas -->
        <div class="w-2/3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">{{ __('Your Page Layout') }}</h3>
                <a href="{{ route('business.landing', ['domain' => $business->domain]) }}"
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                    {{ __('Open Preview') }}
                </a>
            </div>

            <form method="POST" action="{{ route('business.components.reorder', $business) }}">
                @csrf

                <ul id="canvas" class="min-h-[300px] bg-gray-100 p-4 space-y-4 border border-dashed border-gray-300 rounded">
                    @foreach($business->components as $component)
                        <li class="bg-white p-4 rounded shadow drag-target" draggable="true" data-id="{{ $component->id }}">
                            <div class="flex justify-between items-center">
                                <div class="w-full">
                                    <div class="font-bold text-gray-800 mb-1">{{ ucfirst(str_replace('_', ' ', $component->type)) }}</div>
                                    @if($component->type === 'intro_text')
                                        <p class="text-sm text-gray-600">{{ $component->content ?? 'Welcome to our business!' }}</p>
                                    @elseif($component->type === 'image')
                                        <div class="w-full h-24 bg-gray-200 rounded border flex items-center justify-center text-xs text-gray-500">
                                            Image preview placeholder
                                        </div>
                                    @elseif($component->type === 'featured_ads')
                                        <p class="text-sm italic text-gray-500">3 featured advertisements will show here.</p>
                                    @endif
                                </div>
                                <button type="button" class="ml-4 text-xs text-blue-600 hover:underline">
                                    {{ __('Edit') }}
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <input type="hidden" name="order" id="component-order">

                <x-primary-button class="mt-4">
                    {{ __('Save Layout') }}
                </x-primary-button>
            </form>
        </div>
    </div>

    <script>
        const paletteItems = document.querySelectorAll('.draggable');
        const canvas = document.getElementById('canvas');

        paletteItems.forEach(item => {
            item.addEventListener('dragstart', e => {
                e.dataTransfer.setData('type', e.target.dataset.type);
            });
        });

        canvas.addEventListener('dragover', e => e.preventDefault());

        canvas.addEventListener('drop', e => {
            e.preventDefault();
            const type = e.dataTransfer.getData('type');
            if (!type) return;

            const li = document.createElement('li');
            li.className = 'bg-white p-4 rounded shadow drag-target';
            li.setAttribute('draggable', 'true');

            li.innerHTML = `
                <div class="flex justify-between items-center">
                    <div class="w-full">
                        <div class="font-bold text-gray-800 mb-1">${type.replace('_', ' ').toUpperCase()} (new)</div>
                        ${
                type === 'intro_text'
                    ? '<p class="text-sm text-gray-600">This is placeholder intro text.</p>'
                    : type === 'image'
                        ? '<div class="w-full h-24 bg-gray-200 rounded border flex items-center justify-center text-xs text-gray-500">Image Placeholder</div>'
                        : type === 'featured_ads'
                            ? '<p class="text-sm italic text-gray-500">3 featured advertisements will show here.</p>'
                            : ''
            }
                        <input type="hidden" name="new_components[]" value="${type}">
                    </div>
                    <button type="button" class="ml-4 text-xs text-blue-600 hover:underline">
                        Edit
                    </button>
                </div>
            `;

            canvas.appendChild(li);
            enableReordering();
        });

        let draggedEl = null;

        function enableReordering() {
            const items = canvas.querySelectorAll('.drag-target');

            items.forEach(item => {
                item.addEventListener('dragstart', () => {
                    draggedEl = item;
                    setTimeout(() => item.style.display = "none", 0);
                });

                item.addEventListener('dragend', () => {
                    setTimeout(() => item.style.display = "", 0);
                });

                item.addEventListener('dragover', e => {
                    e.preventDefault();
                    item.style.borderTop = "2px solid blue";
                });

                item.addEventListener('dragleave', () => {
                    item.style.borderTop = "none";
                });

                item.addEventListener('drop', () => {
                    item.style.borderTop = "none";
                    canvas.insertBefore(draggedEl, item);
                    updateOrder();
                });
            });
        }

        function updateOrder() {
            const orderInput = document.getElementById('component-order');
            const ids = Array.from(canvas.children).map((li, index) => ({
                id: li.dataset.id,
                order: index
            })).filter(item => item.id);
            orderInput.value = JSON.stringify(ids);
        }

        enableReordering();
        updateOrder();
    </script>
</x-app-layout>
