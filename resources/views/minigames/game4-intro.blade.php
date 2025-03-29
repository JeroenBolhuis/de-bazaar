<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <span class="text-3xl mr-2">⚡</span>
            {{ __('Quick Decision') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-amber-900 to-orange-900 min-h-[calc(100vh-65px)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/80 overflow-hidden shadow-pixel rounded-lg">
                <div class="p-8 text-center">
                    <h3 class="text-3xl font-bold text-white mb-6 animate-neonFlicker">{{ __('How to Play') }}</h3>
                    
                    <!-- Game Preview -->
                    <div class="relative w-full max-w-md mx-auto mb-8 bg-gray-800 rounded-lg p-6">
                        <div class="text-2xl font-bold text-amber-400 mb-6 animate-pulse">
                            "All cats have four legs"
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-green-600 p-4 rounded-lg text-white font-bold">YES ✓</div>
                            <div class="bg-red-600 p-4 rounded-lg text-white font-bold">NO ✗</div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="space-y-4 mb-8 text-lg">
                        <p class="text-gray-300">
                            {{ __('Statements will appear rapidly - decide if they are TRUE or FALSE!') }}
                        </p>
                        <div class="flex flex-col items-center gap-4">
                            <div class="grid grid-cols-3 gap-4 max-w-sm">
                                <div class="bg-gray-800 p-4 rounded-lg">
                                    <p class="text-amber-400 font-bold mb-2">Level 1-3</p>
                                    <p class="text-gray-300">3 seconds</p>
                                </div>
                                <div class="bg-gray-800 p-4 rounded-lg">
                                    <p class="text-amber-400 font-bold mb-2">Level 4-6</p>
                                    <p class="text-gray-300">2 seconds</p>
                                </div>
                                <div class="bg-gray-800 p-4 rounded-lg">
                                    <p class="text-amber-400 font-bold mb-2">Level 7+</p>
                                    <p class="text-gray-300">1 second</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-300">
                            {{ __('Use arrow keys ← → or click buttons. You have 3 lives - be careful not to make mistakes!') }}
                        </p>
                        <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto mt-4">
                            <div class="bg-green-900/50 p-4 rounded-lg">
                                <p class="text-green-400 font-bold">Correct: +1 point</p>
                            </div>
                            <div class="bg-red-900/50 p-4 rounded-lg">
                                <p class="text-red-400 font-bold">Wrong: -1 ❤️</p>
                            </div>
                        </div>
                        <div class="bg-amber-900/50 p-4 rounded-lg max-w-sm mx-auto mt-4">
                            <p class="text-amber-400 font-bold">Complete Level 10 to DOUBLE your score! 🌟</p>
                        </div>
                    </div>

                    <!-- Start Button -->
                    <a href="{{ route('minigames.game4') }}" 
                       class="inline-block px-8 py-4 bg-amber-600 border-2 border-amber-400 rounded-md font-bold text-white text-xl uppercase tracking-widest hover:bg-amber-700 focus:bg-amber-700 active:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition duration-150 shadow-pixel">
                        {{ __('START GAME') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 