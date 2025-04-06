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
                <div class="draggable bg-white p-3 rounded shadow cursor-move" draggable="true" data-type="text_with_image">📝🖼️ Text with Image</div>
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

            <form method="POST" action="{{ route('business.components.reorder', $business) }}" enctype="multipart/form-data">
                @csrf

                <ul id="canvas" class="min-h-[300px] bg-gray-100 p-4 space-y-4 border border-dashed border-gray-300 rounded">
                    @foreach($business->components as $component)
                        <li class="bg-white p-4 rounded shadow drag-target" draggable="true" data-id="{{ $component->id }}">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <div class="font-bold text-gray-800">{{ ucfirst(str_replace('_', ' ', $component->type)) }}</div>
                                    <input type="hidden" name="components[{{ $component->id }}][type]" value="{{ $component->type }}">
                                </div>

                                @if($component->type === 'intro_text')
                                    <div>
                                        <textarea 
                                            name="components[{{ $component->id }}][content]" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            rows="3"
                                        >{{ $component->content }}</textarea>
                                    </div>
                                @elseif($component->type === 'image')
                                    <div>
                                        @if($component->image_path)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($component->image_path) }}" 
                                                     alt="Component image" 
                                                     class="w-full h-48 object-cover rounded">
                                            </div>
                                        @endif
                                        <input 
                                            type="file" 
                                            name="components[{{ $component->id }}][image]" 
                                            accept="image/*"
                                            class="mt-1 block w-full text-sm text-gray-500"
                                        >
                                    </div>
                                @elseif($component->type === 'text_with_image')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Text Content') }}</label>
                                            <textarea 
                                                name="components[{{ $component->id }}][content]" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                rows="3"
                                            >{{ $component->content }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Image') }}</label>
                                            @if($component->image_path)
                                                <div class="mb-2">
                                                    <img src="{{ Storage::url($component->image_path) }}" 
                                                         alt="Component image" 
                                                         class="w-full h-48 object-cover rounded">
                                                </div>
                                            @endif
                                            <input 
                                                type="file" 
                                                name="components[{{ $component->id }}][image]" 
                                                accept="image/*"
                                                class="mt-1 block w-full text-sm text-gray-500"
                                            >
                                        </div>
                                    </div>
                                @elseif($component->type === 'featured_ads')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Advertisement Types to Show') }}</label>
                                            <div class="space-y-2">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" 
                                                           name="components[{{ $component->id }}][ad_types][]" 
                                                           value="listing"
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                           {{ in_array('listing', $component->settings['ad_types'] ?? ['listing']) ? 'checked' : '' }}>
                                                    <span class="ml-2">{{ __('Listings') }}</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" 
                                                           name="components[{{ $component->id }}][ad_types][]" 
                                                           value="rental"
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                           {{ in_array('rental', $component->settings['ad_types'] ?? []) ? 'checked' : '' }}>
                                                    <span class="ml-2">{{ __('Rentals') }}</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" 
                                                           name="components[{{ $component->id }}][ad_types][]" 
                                                           value="auction"
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                           {{ in_array('auction', $component->settings['ad_types'] ?? []) ? 'checked' : '' }}>
                                                    <span class="ml-2">{{ __('Auctions') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <p class="text-sm italic text-gray-500">{{ __('Up to 3 featured advertisements of each selected type will be shown.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                <input type="hidden" name="order" id="component-order">

                <div class="mt-4 flex justify-end">
                    <x-primary-button>
                        {{ __('Save Changes') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const paletteItems = document.querySelectorAll('.draggable');
        const canvas = document.getElementById('canvas');
        let nextTempId = -1;

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

            const tempId = nextTempId--;
            const li = document.createElement('li');
            li.className = 'bg-white p-4 rounded shadow drag-target';
            li.setAttribute('draggable', 'true');
            li.dataset.id = `new_${tempId}`;

            let componentHtml = `
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="font-bold text-gray-800">${type.replace('_', ' ').toUpperCase()}</div>
                        <input type="hidden" name="new_components[${tempId}][type]" value="${type}">
                    </div>
            `;

            if (type === 'intro_text') {
                componentHtml += `
                    <div>
                        <textarea 
                            name="new_components[${tempId}][content]" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            rows="3"
                        ></textarea>
                    </div>
                `;
            } else if (type === 'image') {
                componentHtml += `
                    <div>
                        <input 
                            type="file" 
                            name="new_components[${tempId}][image]" 
                            accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500"
                        >
                    </div>
                `;
            } else if (type === 'text_with_image') {
                componentHtml += `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Text Content') }}</label>
                            <textarea 
                                name="new_components[${tempId}][content]" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                rows="3"
                            ></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Image') }}</label>
                            <input 
                                type="file" 
                                name="new_components[${tempId}][image]" 
                                accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500"
                            >
                        </div>
                    </div>
                `;
            } else if (type === 'featured_ads') {
                componentHtml += `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Advertisement Types to Show') }}</label>
                            <div class="space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="new_components[\${tempId}][ad_types][]" 
                                           value="listing"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                           checked>
                                    <span class="ml-2">{{ __('Listings') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="new_components[\${tempId}][ad_types][]" 
                                           value="rental"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2">{{ __('Rentals') }}</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="new_components[\${tempId}][ad_types][]" 
                                           value="auction"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2">{{ __('Auctions') }}</span>
                                </label>
                            </div>
                        </div>
                        <p class="text-sm italic text-gray-500">{{ __('Up to 3 featured advertisements of each selected type will be shown.') }}</p>
                    </div>
                `;
            }

            componentHtml += '</div>';
            li.innerHTML = componentHtml;

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
