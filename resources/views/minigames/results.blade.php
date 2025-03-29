<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <span class="text-3xl mr-2">🏆</span>
            {{ __('Game Results') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-900 to-purple-900 min-h-[calc(100vh-65px)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Your Score -->
            <div class="bg-gray-900/80 overflow-hidden shadow-pixel rounded-lg p-6 mb-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Your Result') }}</h3>
                    <div class="text-5xl font-bold text-blue-400 mb-4">
                        @if($game_type === 'game1')
                            {{ number_format($score) }} {{ __('points') }}
                        @else
                            {{ __('Level') }} {{ number_format($score) }}
                        @endif
                    </div>
                    <p class="text-gray-300 mb-4">
                        {{ __('You ranked #:rank out of :total players', ['rank' => $rank, 'total' => $totalPlayers]) }}
                    </p>

                    @if($isWorldRecord)
                        <div class="animate-bounce mt-8">
                            <div class="bg-yellow-900/50 p-6 rounded-lg inline-block shadow-pixel">
                                <h4 class="text-2xl font-bold text-yellow-300 animate-neonFlicker mb-2">🏆 {{ __('NEW WORLD RECORD!') }} 🏆</h4>
                                <p class="text-yellow-200">
                                    {{ __('Congratulations! You\'ve earned a 25% discount on all purchases!') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Leaderboard -->
            <div class="bg-gray-900/80 overflow-hidden shadow-pixel rounded-lg p-6">
                <h3 class="text-2xl font-bold text-white mb-6 text-center">{{ __('Top 10 Leaderboard') }}</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-700">
                                <th class="px-4 py-2 text-blue-400">#</th>
                                <th class="px-4 py-2 text-blue-400">{{ __('Player') }}</th>
                                <th class="px-4 py-2 text-blue-400">{{ __('Score') }}</th>
                                <th class="px-4 py-2 text-blue-400">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topScores as $index => $record)
                                <tr class="border-b border-gray-800 {{ $record->user_id === auth()->id() ? 'bg-blue-900/30' : '' }}">
                                    <td class="px-4 py-2 text-gray-300">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-gray-300">{{ $record->user->name }}</td>
                                    <td class="px-4 py-2 text-gray-300">
                                        @if($game_type === 'game1')
                                            {{ number_format($record->score) }} pts
                                        @else
                                            {{ __('Level') }} {{ number_format($record->score) }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-gray-300">{{ $record->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('minigames.' . $game_type . '.intro') }}" 
                   class="inline-flex items-center px-6 py-3 bg-{{ $game_type === 'game1' ? 'blue' : 'green' }}-600 border-2 border-{{ $game_type === 'game1' ? 'blue' : 'green' }}-400 rounded-md font-bold text-white uppercase tracking-widest hover:bg-{{ $game_type === 'game1' ? 'blue' : 'green' }}-700 focus:bg-{{ $game_type === 'game1' ? 'blue' : 'green' }}-700 active:bg-{{ $game_type === 'game1' ? 'blue' : 'green' }}-900 focus:outline-none focus:ring-2 focus:ring-{{ $game_type === 'game1' ? 'blue' : 'green' }}-500 focus:ring-offset-2 transition duration-150">
                    {{ __('Play Again') }}
                </a>
                <a href="{{ route('minigames.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-600 border-2 border-gray-400 rounded-md font-bold text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150">
                    {{ __('Back to Arcade') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout> 