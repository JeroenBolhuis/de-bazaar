<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <span class="text-3xl mr-2">🔢</span>
            {{ __('Number Memory') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-emerald-900 to-green-900 min-h-[calc(100vh-65px)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/80 overflow-hidden shadow-pixel rounded-lg p-6">
                <!-- Game Area -->
                <div id="gameArea" class="text-center">
                    <!-- Level Display -->
                    <div class="mb-4">
                        <span class="text-xl font-bold text-green-400">Level: </span>
                        <span id="level" class="text-2xl font-bold text-white">1</span>
                    </div>

                    <!-- Game Container -->
                    <div id="gameContainer" class="relative h-96 bg-gray-800 rounded-lg flex items-center justify-center">
                        <!-- Number Display -->
                        <div id="numberDisplay" class="text-7xl font-bold text-green-400 tracking-wider"></div>
                        
                        <!-- Input Form -->
                        <form id="inputForm" class="hidden w-full max-w-md">
                            <input type="text" 
                                   id="userInput" 
                                   class="w-full px-4 py-3 text-3xl text-center bg-gray-700 border-2 border-green-400 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                                   pattern="[0-9]*"
                                   inputmode="numeric"
                                   autocomplete="off"
                                   placeholder="Enter the numbers">
                        </form>

                        <!-- Message Display -->
                        <div id="message" class="hidden text-4xl font-bold"></div>
                    </div>

                    <!-- Loading -->
                    <div id="loading" class="hidden mt-8">
                        <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-white mx-auto"></div>
                        <p class="text-white mt-4">{{ __('Calculating results...') }}</p>
                    </div>
                </div>

                <form id="scoreForm" method="POST" action="{{ route('minigames.submit-score') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="game_type" value="game2">
                    <input type="hidden" name="score" id="finalScore">
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .fade-out {
            animation: fadeOut 0.5s forwards;
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
            const numberDisplay = document.getElementById('numberDisplay');
            const inputForm = document.getElementById('inputForm');
            const userInput = document.getElementById('userInput');
            const levelDisplay = document.getElementById('level');
            const message = document.getElementById('message');
            const loading = document.getElementById('loading');
            const scoreForm = document.getElementById('scoreForm');

            let currentLevel = 1;
            let currentNumber = '';
            let gameActive = true;

            // Start game immediately
            startLevel();

            function startLevel() {
                // Generate number sequence
                // Add a new digit every 3 levels (3,6,9,etc)
                const digitCount = Math.floor(currentLevel / 3) + 3;
                currentNumber = generateNumber(digitCount);
                
                // Show the number
                numberDisplay.textContent = currentNumber;
                numberDisplay.classList.remove('hidden');
                inputForm.classList.add('hidden');
                message.classList.add('hidden');

                // Hide number after delay based on level
                // Start with 3 seconds, decrease by 0.1s every level, minimum 1s
                const viewTime = Math.max(1, 3 - (currentLevel * 0.1)) * 1000;
                setTimeout(showInputForm, viewTime);
            }

            function showInputForm() {
                numberDisplay.classList.add('hidden');
                inputForm.classList.remove('hidden');
                userInput.value = '';
                userInput.focus();
            }

            function generateNumber(length) {
                let result = '';
                for(let i = 0; i < length; i++) {
                    result += Math.floor(Math.random() * 10).toString();
                }
                return result;
            }

            inputForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!gameActive) return;

                const userGuess = userInput.value;
                inputForm.classList.add('hidden');

                if (userGuess === currentNumber) {
                    // Correct answer
                    gameContainer.classList.add('correct-flash');
                    message.textContent = '✅ Correct!';
                    message.classList.remove('hidden');
                    message.classList.remove('text-red-500');
                    message.classList.add('text-green-400');
                    currentLevel++;
                    levelDisplay.textContent = currentLevel;

                    setTimeout(() => {
                        gameContainer.classList.remove('correct-flash');
                        startLevel();
                    }, 1000);
                } else {
                    // Wrong answer - game over
                    gameContainer.classList.add('wrong-flash');
                    message.textContent = '❌ Game Over!';
                    message.classList.remove('hidden');
                    message.classList.remove('text-green-400');
                    message.classList.add('text-red-500');
                    gameActive = false;

                    // Submit score (level reached - 1)
                    setTimeout(() => {
                        loading.classList.remove('hidden');
                        document.getElementById('finalScore').value = currentLevel - 1;
                        scoreForm.submit();
                    }, 1500);
                }
            });

            // Only allow numbers in input
            userInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Submit on max digits
            userInput.addEventListener('input', function(e) {
                if (this.value.length === currentNumber.length) {
                    inputForm.dispatchEvent(new Event('submit'));
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 