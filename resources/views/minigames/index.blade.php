<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <span class="text-3xl mr-2">🕹️</span>
            {{ __('De Bazaar Arcade') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-900 to-purple-900 min-h-[calc(100vh-150px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- World Records Section -->
            @if($worldRecords->count() > 0)
                <div class="mb-8">
                    <h3 class="text-3xl font-bold text-center mb-6 text-white animate-neonFlicker">🏆 {{ __('HALL OF FAME') }} 🏆</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        @foreach($worldRecords as $record)
                            <div class="bg-black/50 p-6 rounded-lg shadow-pixel">
                                <div class="text-xl font-bold text-yellow-300 mb-2">
                                    @switch($record->game_type)
                                        @case('game1')
                                            🎯 Quick Click
                                            @break
                                        @case('game2')
                                            🔢 Number Memory
                                            @break
                                        @case('game3')
                                            🎨 Pattern Match
                                            @break
                                        @case('game4')
                                            ⚡ Quick Decision
                                            @break
                                        @default
                                            {{ $record->game_type }}
                                    @endswitch
                                </div>
                                <div class="text-white">
                                    <span class="text-yellow-400">Champion:</span> {{ $record->user->name }}<br>
                                    <span class="text-yellow-400">High Score:</span> {{ number_format($record->score) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Rewards Notice -->
            <div class="mb-8 p-6 bg-yellow-900/30 rounded-lg shadow-pixel">
                <div class="flex items-center justify-center gap-4 text-yellow-300 text-lg mb-4">
                    <span class="text-2xl">🏆</span>
                    <h3 class="font-bold animate-neonFlicker">{{ __('EARN REAL REWARDS!') }}</h3>
                    <span class="text-2xl">🏆</span>
                </div>
                <p class="text-center text-yellow-200">
                    {{ __('Set a world record in any game to earn a 20% discount on all purchases!') }}<br>
                    {{ __('Each world record adds another 20% - collect them all for maximum savings!') }}
                </p>
            </div>

            <!-- Games Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Game 1: Quick Click -->
                <div class="bg-gray-900 rounded-lg overflow-hidden shadow-pixel animate-arcadeFloat">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-b from-blue-500/20 to-transparent"></div>
                        <div class="h-48 bg-gradient-to-r from-blue-600 to-cyan-600 flex items-center justify-center">
                            <span class="text-6xl">🎯</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-white mb-4 animate-neonFlicker">{{ __('Quick Click') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('Chase and click the moving target, but only when it\'s GREEN! You have 10 seconds to get as many correct clicks as possible. Watch out - clicking when it\'s red will cost you points!') }}</p>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('minigames.game1.intro') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 border-2 border-blue-400 rounded-md font-bold text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                                {{ __('PLAY NOW') }}
                            </a>
                            @if($userRecords->where('game_type', 'game1')->isNotEmpty())
                                <div class="text-blue-400">
                                    <span class="block text-sm">Best Score</span>
                                    <span class="text-xl font-bold">{{ number_format($userRecords->where('game_type', 'game1')->max('score')) }} pts</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Game 2: Number Memory -->
                <div class="bg-gray-900 rounded-lg overflow-hidden shadow-pixel animate-arcadeFloat" style="animation-delay: 0.5s">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-b from-green-500/20 to-transparent"></div>
                        <div class="h-48 bg-gradient-to-r from-green-600 to-emerald-600 flex items-center justify-center">
                            <span class="text-6xl">🔢</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-white mb-4 animate-neonFlicker">{{ __('Number Memory') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('Remember the sequence of numbers shown briefly on screen. Your score increases with each correct sequence!') }}</p>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('minigames.game2.intro') }}" 
                               class="inline-flex items-center px-6 py-3 bg-green-600 border-2 border-green-400 rounded-md font-bold text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150">
                                {{ __('PLAY NOW') }}
                            </a>
                            @if($userRecords->where('game_type', 'game2')->isNotEmpty())
                                <div class="text-green-400">
                                    <span class="block text-sm">Highest Level</span>
                                    <span class="text-xl font-bold">{{ number_format($userRecords->where('game_type', 'game2')->max('score')) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Game 3: Pattern Match -->
                <div class="bg-gray-900 rounded-lg overflow-hidden shadow-pixel animate-arcadeFloat" style="animation-delay: 1s">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-b from-purple-500/20 to-transparent"></div>
                        <div class="h-48 bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center">
                            <span class="text-6xl">🎨</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-white mb-4 animate-neonFlicker">{{ __('Pattern Match') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('Watch the pattern of colors and shapes, then recreate it in the correct order! Each level adds more steps to remember.') }}</p>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('minigames.game3.intro') }}" 
                               class="inline-flex items-center px-6 py-3 bg-purple-600 border-2 border-purple-400 rounded-md font-bold text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-150">
                                {{ __('PLAY NOW') }}
                            </a>
                            @if($userRecords->where('game_type', 'game3')->isNotEmpty())
                                <div class="text-purple-400">
                                    <span class="block text-sm">Best Level</span>
                                    <span class="text-xl font-bold">{{ number_format($userRecords->where('game_type', 'game3')->max('score')) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Game 4: Quick Decision -->
                <div class="bg-gray-900 rounded-lg overflow-hidden shadow-pixel animate-arcadeFloat" style="animation-delay: 1.5s">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-b from-amber-500/20 to-transparent"></div>
                        <div class="h-48 bg-gradient-to-r from-amber-600 to-orange-600 flex items-center justify-center">
                            <span class="text-6xl">⚡</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-white mb-4 animate-neonFlicker">{{ __('Quick Decision') }}</h3>
                        <p class="text-gray-300 mb-4">{{ __('Test your speed and knowledge! Decide if statements are true or false before time runs out. Each correct answer earns points, but be careful - wrong answers will cost you!') }}</p>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('minigames.game4.intro') }}" 
                               class="inline-flex items-center px-6 py-3 bg-amber-600 border-2 border-amber-400 rounded-md font-bold text-white uppercase tracking-widest hover:bg-amber-700 focus:bg-amber-700 active:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition duration-150">
                                {{ __('PLAY NOW') }}
                            </a>
                            @if($userRecords->where('game_type', 'game4')->isNotEmpty())
                                <div class="text-amber-400">
                                    <span class="block text-sm">Best Score</span>
                                    <span class="text-xl font-bold">{{ number_format($userRecords->where('game_type', 'game4')->max('score')) }} pts</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Player Achievements -->
            @if($userWorldRecords->count() > 0)
                <div class="w-full text-center inline-block bg-yellow-900/50 p-6 rounded-lg shadow-pixel">
                    <h3 class="text-2xl font-bold text-yellow-300 mb-4">🌟 {{ __('YOUR ACHIEVEMENTS') }} 🌟</h3>
                    <p class="text-yellow-200 text-xl">
                        {{ __('Legendary! You hold :count world record(s)!', ['count' => $userWorldRecords->count()]) }}
                    </p>
                    <p class="text-yellow-300 text-2xl mt-2 animate-neonFlicker">
                        {{ __('Current Discount: :discount%', ['discount' => $discount]) }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 