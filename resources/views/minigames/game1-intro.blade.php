<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <span class="text-3xl mr-2">🎯</span>
            {{ __('Quick Click') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-900 to-purple-900 min-h-[calc(100vh-65px)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/80 overflow-hidden shadow-pixel rounded-lg">
                <div class="p-8 text-center">
                    <h3 class="text-3xl font-bold text-white mb-6 animate-neonFlicker">{{ __('How to Play') }}</h3>
                    
                    <!-- Game Preview -->
                    <div class="relative w-32 h-32 mx-auto mb-8">
                        <div class="absolute inset-0 w-full h-full rounded-full animate-ping bg-green-500/30"></div>
                        <div class="relative w-full h-full rounded-full bg-green-500 flex items-center justify-center text-4xl">
                            🎯
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="space-y-4 mb-8 text-lg">
                        <p class="text-gray-300">
                            {{ __('Chase the moving target around the screen!') }}
                        </p>
                        <div class="flex items-center justify-center gap-8">
                            <div class="text-center">
                                <div class="w-16 h-16 rounded-full bg-green-500 mb-2 mx-auto flex items-center justify-center text-2xl">
                                    🎯
                                </div>
                                <p class="text-green-400">+1 point</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-16 rounded-full bg-red-600 mb-2 mx-auto flex items-center justify-center text-2xl">
                                    🎯
                                </div>
                                <p class="text-red-400">-2 points</p>
                            </div>
                        </div>
                        <p class="text-gray-300">
                            {{ __('You have 10 seconds - score as many points as you can!') }}
                        </p>
                    </div>

                    <!-- Start Button -->
                    <form action="{{ route('minigames.game1') }}" method="GET" class="inline-block">
                        <button type="submit" 
                                class="px-8 py-4 bg-blue-600 border-2 border-blue-400 rounded-md font-bold text-white text-xl uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 shadow-pixel">
                            {{ __('START GAME') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 