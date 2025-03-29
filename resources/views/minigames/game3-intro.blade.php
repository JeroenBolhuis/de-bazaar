<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <span class="text-3xl mr-2">🎨</span>
            {{ __('Pattern Match') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-purple-900 to-pink-900 min-h-[calc(100vh-65px)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/80 overflow-hidden shadow-pixel rounded-lg">
                <div class="p-8 text-center">
                    <h3 class="text-3xl font-bold text-white mb-6 animate-neonFlicker">{{ __('How to Play') }}</h3>
                    
                    <!-- Game Preview -->
                    <div class="relative w-64 h-64 mx-auto mb-8 bg-gray-800 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4 h-full">
                            <div class="bg-red-500 rounded-lg animate-pulse"></div>
                            <div class="bg-blue-500 rounded-lg"></div>
                            <div class="bg-yellow-500 rounded-lg"></div>
                            <div class="bg-green-500 rounded-lg animate-pulse"></div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="space-y-4 mb-8 text-lg">
                        <p class="text-gray-300">
                            {{ __('Watch the pattern of colors and shapes, then recreate it in the correct order!') }}
                        </p>
                        <div class="flex flex-col items-center gap-4">
                            <div class="grid grid-cols-3 gap-4 max-w-sm">
                                <div class="bg-gray-800 p-4 rounded-lg">
                                    <p class="text-purple-400 font-bold mb-2">Levels 1-3</p>
                                    <p class="text-gray-300">3 steps</p>
                                </div>
                                <div class="bg-gray-800 p-4 rounded-lg">
                                    <p class="text-purple-400 font-bold mb-2">Levels 4-6</p>
                                    <p class="text-gray-300">4 steps</p>
                                </div>
                                <div class="bg-gray-800 p-4 rounded-lg">
                                    <p class="text-purple-400 font-bold mb-2">Every 3 Levels</p>
                                    <p class="text-gray-300">+1 step</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-300">
                            {{ __('Each correct sequence advances you to the next level. How far can you go?') }}
                        </p>
                    </div>

                    <!-- Start Button -->
                    <a href="{{ route('minigames.game3') }}" 
                       class="inline-block px-8 py-4 bg-purple-600 border-2 border-purple-400 rounded-md font-bold text-white text-xl uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-150 shadow-pixel">
                        {{ __('START GAME') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 