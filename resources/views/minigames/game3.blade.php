<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <span class="text-3xl mr-2">🎨</span>
            {{ __('Pattern Match') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-purple-900 to-pink-900 min-h-[calc(100vh-65px)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/80 overflow-hidden shadow-pixel rounded-lg p-6">
                <!-- Game Area -->
                <div id="gameArea" class="text-center">
                    <!-- Level Display -->
                    <div class="mb-4">
                        <span class="text-xl font-bold text-purple-400">Level: </span>
                        <span id="level" class="text-2xl font-bold text-white">1</span>
                    </div>

                    <!-- Game Container -->
                    <div id="gameContainer" class="relative bg-gray-800 rounded-lg p-6">
                        <!-- Pattern Display -->
                        <div id="patternDisplay" class="grid grid-cols-2 gap-4 max-w-md mx-auto mb-8">
                            <button class="h-32 rounded-lg transition-all duration-200 transform hover:scale-95 active:scale-90 bg-red-500" data-color="red"></button>
                            <button class="h-32 rounded-lg transition-all duration-200 transform hover:scale-95 active:scale-90 bg-blue-500" data-color="blue"></button>
                            <button class="h-32 rounded-lg transition-all duration-200 transform hover:scale-95 active:scale-90 bg-yellow-500" data-color="yellow"></button>
                            <button class="h-32 rounded-lg transition-all duration-200 transform hover:scale-95 active:scale-90 bg-green-500" data-color="green"></button>
                        </div>

                        <!-- Message Display -->
                        <div id="message" class="text-2xl font-bold mb-4 h-8"></div>

                        <!-- Progress Display -->
                        <div id="progress" class="text-lg text-purple-400 mb-4"></div>
                    </div>

                    <!-- Loading -->
                    <div id="loading" class="hidden mt-8">
                        <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-white mx-auto"></div>
                        <p class="text-white mt-4">{{ __('Calculating results...') }}</p>
                    </div>
                </div>

                <form id="scoreForm" method="POST" action="{{ route('minigames.submit-score') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="game_type" value="game3">
                    <input type="hidden" name="score" id="finalScore">
                </form>
            </div>
        </div>
    </div>

    <style>
        .highlight {
            filter: brightness(1.5);
            transform: scale(1.1);
        }

        .correct-flash {
            animation: correctFlash 0.5s;
        }

        @keyframes correctFlash {
            0% { background-color: rgba(34, 197, 94, 0); }
            50% { background-color: rgba(34, 197, 94, 0.2); }
            100% { background-color: rgba(34, 197, 94, 0); }
        }

        .wrong-flash {
            animation: wrongFlash 0.5s;
        }

        @keyframes wrongFlash {
            0% { background-color: rgba(239, 68, 68, 0); }
            50% { background-color: rgba(239, 68, 68, 0.2); }
            100% { background-color: rgba(239, 68, 68, 0); }
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gameContainer = document.getElementById('gameContainer');
            const patternDisplay = document.getElementById('patternDisplay');
            const buttons = patternDisplay.querySelectorAll('button');
            const levelDisplay = document.getElementById('level');
            const message = document.getElementById('message');
            const progress = document.getElementById('progress');
            const loading = document.getElementById('loading');
            const scoreForm = document.getElementById('scoreForm');

            const colors = ['red', 'blue', 'yellow', 'green'];
            let currentLevel = 1;
            let pattern = [];
            let playerPattern = [];
            let gameActive = false;
            let canClick = false;

            // Start game immediately
            startLevel();

            function startLevel() {
                // Generate pattern length based on level
                const patternLength = Math.floor((currentLevel - 1) / 3) + 3;
                pattern = generatePattern(patternLength);
                playerPattern = [];
                gameActive = true;
                canClick = false;

                message.textContent = 'Watch the pattern...';
                message.className = 'text-2xl font-bold mb-4 h-8 text-white';
                progress.textContent = '';

                // Show the pattern
                showPattern();
            }

            function generatePattern(length) {
                let newPattern = [];
                for(let i = 0; i < length; i++) {
                    newPattern.push(colors[Math.floor(Math.random() * colors.length)]);
                }
                return newPattern;
            }

            async function showPattern() {
                // Disable buttons during pattern display
                buttons.forEach(btn => btn.disabled = true);

                // Show each color in sequence
                for(let color of pattern) {
                    await highlightButton(color);
                }

                // Enable buttons and allow player to start
                buttons.forEach(btn => btn.disabled = false);
                canClick = true;
                message.textContent = 'Your turn!';
                progress.textContent = `0/${pattern.length} steps`;
            }

            function highlightButton(color) {
                return new Promise(resolve => {
                    const button = patternDisplay.querySelector(`[data-color="${color}"]`);
                    button.classList.add('highlight');
                    
                    // Delay based on level (faster at higher levels)
                    const delay = Math.max(500, 1000 - (currentLevel * 50));
                    
                    setTimeout(() => {
                        button.classList.remove('highlight');
                        setTimeout(resolve, 200); // Gap between highlights
                    }, delay);
                });
            }

            buttons.forEach(button => {
                button.addEventListener('click', async () => {
                    if (!gameActive || !canClick) return;

                    const color = button.dataset.color;
                    playerPattern.push(color);
                    
                    // Highlight the clicked button
                    button.classList.add('highlight');
                    setTimeout(() => button.classList.remove('highlight'), 200);

                    // Update progress
                    progress.textContent = `${playerPattern.length}/${pattern.length} steps`;

                    // Check if the move was correct
                    if (color !== pattern[playerPattern.length - 1]) {
                        gameOver();
                        return;
                    }

                    // Check if pattern is complete
                    if (playerPattern.length === pattern.length) {
                        await levelComplete();
                    }
                });
            });

            async function levelComplete() {
                gameActive = false;
                canClick = false;
                gameContainer.classList.add('correct-flash');
                message.textContent = '✅ Correct!';
                message.classList.remove('text-white');
                message.classList.add('text-green-400');

                currentLevel++;
                levelDisplay.textContent = currentLevel;

                await new Promise(resolve => setTimeout(resolve, 1000));
                gameContainer.classList.remove('correct-flash');
                startLevel();
            }

            function gameOver() {
                gameActive = false;
                canClick = false;
                gameContainer.classList.add('wrong-flash');
                message.textContent = '❌ Game Over!';
                message.classList.remove('text-white', 'text-green-400');
                message.classList.add('text-red-500');

                // Submit score (level reached - 1)
                setTimeout(() => {
                    loading.classList.remove('hidden');
                    document.getElementById('finalScore').value = currentLevel - 1;
                    scoreForm.submit();
                }, 1500);
            }
        });
    </script>
    @endpush
</x-app-layout> 