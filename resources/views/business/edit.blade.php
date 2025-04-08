<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-4">{{ __('Edit Landing Page Components') }}</h1>
                <p class="text-gray-600">{{ __('Add, remove, and reorder components for your landing page.') }}</p>
            </div>
            <a href="{{ route('business.show', ['customUrl' => auth()->user()->business->custom_url]) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back to Landing Page') }}
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add New Component Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('Add New Component') }}</h2>
            <form action="{{ route('business.components.add') }}" method="POST" class="flex gap-4">
                @csrf
                <select name="type" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">{{ __('Select component type') }}</option>
                    @foreach($availableTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Add Component') }}
                </button>
            </form>
        </div>

        <!-- Component List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">{{ __('Current Components') }}</h2>
            
            @if($components->isEmpty())
                <p class="text-gray-500 text-center py-8">{{ __('No components added yet.') }}</p>
            @else
                <div class="space-y-4">
                    @foreach($components as $index => $component)
                        <div class="flex flex-col gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg" data-id="{{ $component->pivot->id }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="mb-2">
                                        <span class="text-sm text-gray-500">{{ __('Type:') }}</span>
                                        <span class="font-medium">{{ $component->type }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-sm text-gray-500">{{ __('Title:') }}</span>
                                        <span class="font-medium">{{ $component->pivot->title }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-sm text-gray-500">{{ __('Content:') }}</span>
                                        <span class="font-medium">{{ $component->pivot->content }}</span>
                                    </div>
                                    
                                    <!-- Edit Form (Hidden by default) -->
                                    <form action="{{ route('business.components.update', $component->pivot->id) }}" method="POST" class="hidden edit-form flex flex-col gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="title" value="{{ $component->pivot->title }}" placeholder="{{ __('Component Title') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <textarea name="content" rows="3" placeholder="{{ __('Component Content') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ $component->pivot->content }}</textarea>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" class="cancel-edit bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                                {{ __('Cancel') }}
                                            </button>
                                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                {{ __('Save Changes') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <!-- Edit Button -->
                                    <button type="button" class="edit-button text-gray-400 hover:text-gray-600" title="{{ __('Edit component') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <!-- Move Up/Down Form -->
                                    <form action="{{ route('business.components.reorder') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="orders[]" value="{{ $component->pivot->id }}">
                                        
                                        <!-- Move Up Button -->
                                        @if($index > 0)
                                            <button type="submit" name="move_up" value="{{ $component->pivot->id }}" class="text-gray-400 hover:text-gray-600" title="{{ __('Move up') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <span class="w-5 h-5"></span>
                                        @endif

                                        <!-- Move Down Button -->
                                        @if($index < $components->count() - 1)
                                            <button type="submit" name="move_down" value="{{ $component->pivot->id }}" class="text-gray-400 hover:text-gray-600" title="{{ __('Move down') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </form>
                                    
                                    <!-- Delete Form -->
                                    <form action="{{ route('business.components.delete', $component->pivot->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600" onclick="return confirm('{{ __('Are you sure you want to delete this component?') }}')" title="{{ __('Delete component') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all edit buttons and forms
    const editButtons = document.querySelectorAll('.edit-button');
    const editForms = document.querySelectorAll('.edit-form');
    const cancelButtons = document.querySelectorAll('.cancel-edit');

    // Add click event listeners to edit buttons
    editButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            // Hide all other edit forms
            editForms.forEach(form => form.classList.add('hidden'));
            
            // Show the corresponding edit form
            editForms[index].classList.remove('hidden');
            
            // Hide the component details
            const componentDetails = editForms[index].previousElementSibling;
            while (componentDetails && !componentDetails.classList.contains('mb-2')) {
                componentDetails.style.display = 'none';
                componentDetails = componentDetails.previousElementSibling;
            }
        });
    });

    // Add click event listeners to cancel buttons
    cancelButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            // Hide the edit form
            editForms[index].classList.add('hidden');
            
            // Show the component details
            const componentDetails = editForms[index].previousElementSibling;
            while (componentDetails && !componentDetails.classList.contains('mb-2')) {
                componentDetails.style.display = 'block';
                componentDetails = componentDetails.previousElementSibling;
            }
        });
    });

    // Prevent form submission on textarea focus
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('focus', (e) => {
            e.preventDefault();
        });
    });

    // Add form submission debugging
    document.querySelectorAll('.edit-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const formData = new FormData(form);
            console.log('Form data:', {
                title: formData.get('title'),
                content: formData.get('content')
            });
        });
    });
});
</script>