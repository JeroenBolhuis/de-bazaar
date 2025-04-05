<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Business Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Domain Settings Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Custom Domain Settings') }}</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            {{ __('Set up your custom domain for your business landing page. This will allow customers to visit your business through a unique URL.') }}
                        </p>
                        
                        <form method="POST" action="{{ route('business.domain.update') }}" class="space-y-4">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <x-input-label for="domain" :value="__('Custom Domain')" />
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        https://
                                    </span>
                                    <x-text-input
                                        id="domain"
                                        name="domain"
                                        type="text"
                                        class="block w-full rounded-none rounded-r-md"
                                        :value="old('domain', auth()->user()->business->domain ?? '')"
                                        placeholder="your-business.de-real-bazaar.com"
                                        required
                                    />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('domain')" />
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ __('Your custom domain will be available at:') }} 
                                    <span class="font-medium">http://127.0.0.1:8000/<span id="domain-preview">{{ old('domain', auth()->user()->business->domain ?? '') }}</span></span>
                                </p>
                                @if(auth()->user()->business->domain)
                                    <div class="mt-4">
                                        <a href="http://127.0.0.1:8000/{{ auth()->user()->business->domain }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('Visit Your Business Page') }}
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Save Domain Settings') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Theme Settings Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Theme Settings') }}</h3>
                        <form method="POST" action="{{ route('business.theme.update') }}" class="space-y-4">
                            @csrf
                            @method('POST')
                            
                            <div>
                                <x-input-label for="primary_color" :value="__('Primary Color')" />
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        #
                                    </span>
                                    <x-text-input
                                        id="primary_color"
                                        name="primary_color"
                                        type="text"
                                        class="block w-full rounded-none rounded-r-md"
                                        :value="old('primary_color', auth()->user()->business->theme_settings['primary_color'] ?? '#3B82F6')"
                                        placeholder="3B82F6"
                                        required
                                    />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('primary_color')" />
                            </div>

                            <div>
                                <x-input-label for="secondary_color" :value="__('Secondary Color')" />
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        #
                                    </span>
                                    <x-text-input
                                        id="secondary_color"
                                        name="secondary_color"
                                        type="text"
                                        class="block w-full rounded-none rounded-r-md"
                                        :value="old('secondary_color', auth()->user()->business->theme_settings['secondary_color'] ?? '#10B981')"
                                        placeholder="10B981"
                                        required
                                    />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('secondary_color')" />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Save Theme Settings') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('domain').addEventListener('input', function(e) {
            document.getElementById('domain-preview').textContent = e.target.value;
        });
    </script>
    @endpush
</x-app-layout> 