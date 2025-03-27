<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-8">{{ __('Create Listing') }}</h2>

                <form method="POST" action="{{ route('advertisements.store') }}" class="mt-3 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    @csrf
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="mt-1 block w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" class="mt-1 block w-full" type="number" name="price" :value="old('price')" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="description" :value="__('Description')" />
                            <x-textarea-input id="description" class="mt-1 block w-full" name="description" :value="old('description')" required />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="location" :value="__('Location')" />
                            <x-text-input id="location" class="mt-1 block w-full" type="text" name="location" :value="old('location')" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="image" :value="__('Image')" />
                            <x-file-input id="image" class="mt-1 block w-full" name="image" :value="old('image')" required />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                    </div>
                    <button type="submit" class="mt-6 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Create Listing') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
                        
                        
                        
                        
                        
                        
                    
