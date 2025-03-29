<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <span class="text-3xl mr-2">🎯</span>
            {{ __('Quick Click') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-900 to-purple-900 min-h-[calc(100vh-65px)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/80 overflow-hidden shadow-pixel rounded-lg p-6">
                <!-- Game Area -->
                <div id="gameArea" class="text-center">
                    <!-- Game Container -->
                    <div id="gameContainer" class="relative h-96 overflow-hidden rounded-lg">
                        <!-- Timer Bar -->
                        <div id="timerBar" class="absolute top-0 left-0 w-full h-2 bg-blue-600 origin-left scale-x-100"></div>
                        
                        <!-- Score -->
                        <div id="score" class="absolute top-4 right-4 text-2xl font-bold text-white">
                            <span id="scoreValue">0</span> pts
                        </div>

                        <!-- Moving Target -->
                        <div id="target" 
                             class="absolute w-16 h-16 rounded-full flex items-center justify-center text-2xl cursor-pointer">
                            🎯
                        </div>
                    </div>

                    <!-- Loading -->
                    <div id="loading" class="hidden mt-8">
                        <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-white mx-auto"></div>
                        <p class="text-white mt-4">{{ __('Calculating results...') }}</p>
                    </div>
                </div>

                <form id="scoreForm" method="POST" action="{{ route('minigames.submit-score') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="game_type" value="game1">
                    <input type="hidden" name="score" id="finalScore">
                </form>
            </div>
        </div>
    </div>

    <style>
        #gameContainer {
            background: repeating-linear-gradient(
                45deg,
                rgba(30, 41, 59, 0.7),
                rgba(30, 41, 59, 0.7) 10px,
                rgba(30, 41, 59, 0.8) 10px,
                rgba(30, 41, 59, 0.8) 20px
            );
        }

        #target {
            transition: background-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }

        #target.target-green {
            background-color: rgb(34 197 94);
            transform: scale(1.1);
            box-shadow: 0 0 20px rgb(34 197 94);
        }

        #target.target-red {
            background-color: rgb(220 38 38);
            box-shadow: 0 0 10px rgb(220 38 38);
        }

        @keyframes moveTarget {
            0% { left: 0; top: 0; }
            25% { left: calc(100% - 4rem); top: calc(100% - 4rem); }
            50% { left: calc(100% - 4rem); top: 0; }
            75% { left: 0; top: calc(100% - 4rem); }
            100% { left: 0; top: 0; }
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gameContainer = document.getElementById('gameContainer');
            const target = document.getElementById('target');
            const loading = document.getElementById('loading');
            const scoreForm = document.getElementById('scoreForm');
            const scoreValue = document.getElementById('scoreValue');
            const timerBar = document.getElementById('timerBar');

            let score = 0;
            let gameActive = false;
            let colorChangeInterval;

            // Start game immediately
            startGame();

            target.addEventListener('click', handleClick);

            function startGame() {
                score = 0;
                scoreValue.textContent = '0';
                gameActive = true;

                // Start target movement
                target.style.animation = 'moveTarget 20s linear infinite';

                // Start color changing
                target.classList.add('target-red');
                startColorChange();

                // Start timer
                requestAnimationFrame(() => {
                    timerBar.style.transition = 'transform 10s linear';
                    timerBar.style.transform = 'scaleX(0)';
                });

                // End game after 10 seconds
                setTimeout(finishGame, 10000);
            }

            function startColorChange() {
                colorChangeInterval = setInterval(() => {
                    if (Math.random() < 0.75) {
                        target.classList.remove('target-red');
                        target.classList.add('target-green');
                    } else {
                        target.classList.remove('target-green');
                        target.classList.add('target-red');
                    }
                }, Math.random() * 1500 + 500); // Random interval between 0.5 and 2 seconds
            }

            function handleClick() {
                if (!gameActive) return;

                if (target.classList.contains('target-green')) {
                    score += 1;
                    // Add hit effect
                    target.style.transform = 'scale(1.3)';
                    setTimeout(() => target.style.transform = '', 100);
                } else {
                    score -= 2;
                    // Add miss effect
                    gameContainer.style.backgroundColor = 'rgba(220, 38, 38, 0.2)';
                    setTimeout(() => gameContainer.style.backgroundColor = '', 100);
                }
                scoreValue.textContent = score;
            }

            function finishGame() {
                gameActive = false;
                clearInterval(colorChangeInterval);
                target.style.animation = 'none';
                loading.classList.remove('hidden');
                document.getElementById('finalScore').value = score;
                scoreForm.submit();
            }
        });
    </script>
    @endpush
</x-app-layout> 