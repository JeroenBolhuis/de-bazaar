<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
            <span class="text-3xl mr-2">⚡</span>
            {{ __('Quick Decision') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-amber-900 to-orange-900 min-h-[calc(100vh-65px)]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/80 overflow-hidden shadow-pixel rounded-lg p-6">
                <!-- Game Area -->
                <div id="gameArea" class="text-center">
                    <!-- Score and Lives Display -->
                    <div class="mb-4 flex justify-between items-center max-w-md mx-auto">
                        <div>
                            <span class="text-xl font-bold text-amber-400">Score: </span>
                            <span id="score" class="text-2xl font-bold text-white">0</span>
                        </div>
                        <div>
                            <span id="lives" class="text-2xl font-bold text-red-500">❤️❤️❤️</span>
                        </div>
                    </div>

                    <!-- Game Container -->
                    <div id="gameContainer" class="relative bg-gray-800 rounded-lg p-6">
                        <!-- Timer Bar -->
                        <div id="timerBar" class="absolute top-0 left-0 w-full h-2 bg-amber-600 origin-left scale-x-100"></div>

                        <!-- Statement Display -->
                        <div class="min-h-[200px] flex items-center justify-center">
                            <div id="statement" class="text-3xl font-bold text-amber-400 max-w-2xl"></div>
                        </div>

                        <!-- Buttons -->
                        <div class="grid grid-cols-2 gap-4 max-w-md mx-auto">
                            <button id="trueBtn" class="p-6 bg-green-600 hover:bg-green-700 active:bg-green-800 rounded-lg text-white font-bold text-xl transition-all transform hover:scale-95 active:scale-90">
                                TRUE ✓
                            </button>
                            <button id="falseBtn" class="p-6 bg-red-600 hover:bg-red-700 active:bg-red-800 rounded-lg text-white font-bold text-xl transition-all transform hover:scale-95 active:scale-90">
                                FALSE ✗
                            </button>
                        </div>

                        <!-- Level Display -->
                        <div id="level" class="mt-4 text-lg text-amber-400"></div>
                    </div>

                    <!-- Loading -->
                    <div id="loading" class="hidden mt-8">
                        <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-white mx-auto"></div>
                        <p class="text-white mt-4">{{ __('Calculating results...') }}</p>
                    </div>
                </div>

                <form id="scoreForm" method="POST" action="{{ route('minigames.submit-score') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="game_type" value="game4">
                    <input type="hidden" name="score" id="finalScore">
                </form>
            </div>
        </div>
    </div>

    <style>
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

        @keyframes timerAnimation {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gameContainer = document.getElementById('gameContainer');
            const statement = document.getElementById('statement');
            const trueBtn = document.getElementById('trueBtn');
            const falseBtn = document.getElementById('falseBtn');
            const scoreDisplay = document.getElementById('score');
            const livesDisplay = document.getElementById('lives');
            const levelDisplay = document.getElementById('level');
            const timerBar = document.getElementById('timerBar');
            const loading = document.getElementById('loading');
            const scoreForm = document.getElementById('scoreForm');

            let currentLevel = 1;
            let score = 0;
            let lives = 3;
            let gameActive = true;
            let currentStatement = null;
            let currentTimer = null;
            let answerLocked = false;
            let questionsPerLevel = 3;
            let questionsAnsweredInLevel = 0;
            let availableStatements = [];
            let statements = [
                // Level 1 - Simple Facts
                { text: "The moon orbits the Earth", isTrue: true, level: 1 },
                { text: "Fire is cold", isTrue: false, level: 1 },
                { text: "Birds have feathers", isTrue: true, level: 1 },
                { text: "Dogs are reptiles", isTrue: false, level: 1 },
                { text: "The Earth is round", isTrue: true, level: 1 },
                { text: "Cats can fly", isTrue: false, level: 1 },
                { text: "Water freezes at 0°C", isTrue: true, level: 1 },
                { text: "The sun is a planet", isTrue: false, level: 1 },
                { text: "Birds lay eggs", isTrue: true, level: 1 },
                { text: "Fish can breathe air", isTrue: false, level: 1 },
                
                // Level 2 - Slightly Harder
                { text: "Plants need sunlight to make food", isTrue: true, level: 2 },
                { text: "Sharks are mammals", isTrue: false, level: 2 },
                { text: "The Earth revolves around the Sun", isTrue: true, level: 2 },
                { text: "Bees make honey", isTrue: true, level: 2 },
                { text: "Humans need oxygen to live", isTrue: true, level: 2 },
                { text: "All birds can fly", isTrue: false, level: 2 },
                { text: "The sky appears blue", isTrue: true, level: 2 },
                { text: "Fish can breathe underwater", isTrue: true, level: 2 },
                { text: "Trees produce oxygen", isTrue: true, level: 2 },
                { text: "A triangle has four sides", isTrue: false, level: 2 },
                
                // Level 3 - Starting to Trick
                { text: "Milk is a plant-based drink", isTrue: false, level: 3 },
                { text: "Venus is the hottest planet in our Solar System", isTrue: true, level: 3 },
                { text: "A hexagon has five sides", isTrue: false, level: 3 },
                { text: "Mercury is the closest planet to the Sun", isTrue: true, level: 3 },
                { text: "A square has 3 sides", isTrue: false, level: 3 },
                { text: "Penguins live in Antarctica", isTrue: true, level: 3 },
                { text: "Venus is hotter than Mercury", isTrue: true, level: 3 },
                { text: "Plants make food through photosynthesis", isTrue: true, level: 3 },
                { text: "Sharks are mammals", isTrue: false, level: 3 },
                { text: "Bees make honey", isTrue: true, level: 3 },
                
                // Level 4 - Common Misconceptions
                { text: "Lightning always strikes from the sky downwards", isTrue: false, level: 4 },
                { text: "Humans have more than five senses", isTrue: true, level: 4 },
                { text: "An octopus has three hearts", isTrue: true, level: 4 },
                { text: "All spiders have eight eyes", isTrue: false, level: 4 },
                { text: "A tomato is a fruit", isTrue: true, level: 4 },
                { text: "All mammals lay eggs", isTrue: false, level: 4 },
                { text: "Lightning always strikes from the sky downwards", isTrue: false, level: 4 },
                { text: "Humans have more than five senses", isTrue: true, level: 4 },
                { text: "An octopus has three hearts", isTrue: true, level: 4 },
                { text: "Gold is heavier than silver", isTrue: true, level: 4 },
                
                // Level 5 - Brain Twisters
                { text: "Bananas grow on trees", isTrue: false, level: 5 },
                { text: "Birds are descendants of dinosaurs", isTrue: true, level: 5 },
                { text: "Humans have 206 bones in their bodies", isTrue: true, level: 5 },
                { text: "Sound travels faster than light", isTrue: false, level: 5 },
                { text: "The Great Wall of China is visible from space", isTrue: false, level: 5 },
                { text: "Bats are blind", isTrue: false, level: 5 },
                { text: "Bananas grow on trees", isTrue: false, level: 5 },
                { text: "Birds are descendants of dinosaurs", isTrue: true, level: 5 },
                { text: "Milk is a plant-based drink", isTrue: false, level: 5 },
                { text: "Humans have 206 bones in their bodies", isTrue: true, level: 5 },
                
                // Level 6 - Knowledge Test
                { text: "Venomous and poisonous mean the same thing", isTrue: false, level: 6 },
                { text: "The Amazon rainforest produces over 20% of the world's oxygen", isTrue: false, level: 6 },
                { text: "Saturn is the only planet with rings", isTrue: false, level: 6 },
                { text: "Water is composed of hydrogen and oxygen", isTrue: true, level: 6 },
                { text: "Lightning never strikes the same place twice", isTrue: false, level: 6 },
                { text: "Humans share 50% of their DNA with bananas", isTrue: true, level: 6 },
                { text: "Venomous and poisonous mean the same thing", isTrue: false, level: 6 },
                { text: "Saturn is the only planet with rings", isTrue: false, level: 6 },
                { text: "Water is composed of hydrogen and oxygen", isTrue: true, level: 6 },
                { text: "The Amazon rainforest produces over 20% of the world's oxygen", isTrue: false, level: 6 },

                
                // Level 7 - Science & Trivia
                { text: "Hot water freezes faster than cold water", isTrue: true, level: 7 },
                { text: "Koalas are bears", isTrue: false, level: 7 },
                { text: "A group of flamingos is called a flamboyance", isTrue: true, level: 7 },
                { text: "The speed of light is constant everywhere", isTrue: false, level: 7 },
                { text: "The tongue is the strongest muscle in the body", isTrue: false, level: 7 },
                { text: "Mount Everest is the tallest mountain on Earth", isTrue: false, level: 7 },
                { text: "Hot water freezes faster than cold water", isTrue: true, level: 7 },
                { text: "Koalas are bears", isTrue: false, level: 7 },
                { text: "A group of flamingos is called a flamboyance", isTrue: true, level: 7 },
                { text: "The speed of light is constant everywhere", isTrue: false, level: 7 },

                
                // Level 8 - Mind-Bending Facts
                { text: "Humans glow in the dark, but it's too dim to detect", isTrue: true, level: 8 },
                { text: "A year on Mercury is shorter than a day on Mercury", isTrue: true, level: 8 },
                { text: "There are more stars in the universe than grains of sand on Earth", isTrue: true, level: 8 },
                { text: "The Eiffel Tower is shorter in winter", isTrue: true, level: 8 },
                { text: "Humans glow in the dark, but it's too dim to detect", isTrue: true, level: 8 },
                { text: "A day on Venus is longer than its year", isTrue: true, level: 8 },
                { text: "A year on Mercury is shorter than a day on Mercury", isTrue: true, level: 8 },
                { text: "The Eiffel Tower is shorter in winter", isTrue: true, level: 8 },
                { text: "Goldfish have a 3-second memory", isTrue: false, level: 8 },
                { text: "Time passes faster at the top of a mountain than at sea level", isTrue: true, level: 8 },

                
                // Level 9 - Extreme Trivia
                { text: "Glass is a solid", isTrue: false, level: 9 },
                { text: "Octopuses have blue blood", isTrue: true, level: 9 },
                { text: "There are no muscles in your fingers", isTrue: true, level: 9 },
                { text: "The Sahara Desert is the largest desert on Earth", isTrue: false, level: 9 },
                { text: "Glass is a solid", isTrue: false, level: 9 },
                { text: "Octopuses have blue blood", isTrue: true, level: 9 },
                { text: "There are no muscles in your fingers", isTrue: true, level: 9 },
                { text: "The Sahara Desert is the largest desert on Earth", isTrue: false, level: 9 },
                { text: "Sharks have bones", isTrue: false, level: 9 },
                { text: "Birds are warm-blooded animals", isTrue: true, level: 9 },
                
                // Level 10 - The Ultimate Test
                { text: "The human body contains enough iron to make a small nail", isTrue: true, level: 10 },
                { text: "Antarctica is the driest place on Earth", isTrue: true, level: 10 },
                { text: "Sharks have bones", isTrue: false, level: 10 },
                { text: "Time passes faster at the top of a mountain than at sea level", isTrue: true, level: 10 },
                { text: "The human body contains enough iron to make a small nail", isTrue: true, level: 10 },
                { text: "Antarctica is the driest place on Earth", isTrue: true, level: 10 },
                { text: "The sun is mostly made of helium", isTrue: false, level: 10 },
                { text: "Black holes emit no radiation", isTrue: false, level: 10 },
                { text: "Some metals can explode in water", isTrue: true, level: 10 },
                { text: "Saturn could float in water", isTrue: true, level: 10 },
                { text: "Light can behave both as a wave and a particle", isTrue: true, level: 10 },
                { text: "Humans have more than two types of photoreceptor cells in their eyes", isTrue: true, level: 10 },
                { text: "Absolute zero is the hottest temperature possible", isTrue: false, level: 10 },
                { text: "Some animals can photosynthesize", isTrue: true, level: 10 },
                { text: "Quantum entanglement allows faster-than-light communication", isTrue: false, level: 10 },
                { text: "Neutrinos can pass through the Earth without interaction", isTrue: true, level: 10 },
                { text: "The majority of matter in the universe is dark matter", isTrue: true, level: 10 },
                { text: "Electrons are smaller than quarks", isTrue: false, level: 10 },
                { text: "The observable universe is expanding at an accelerating rate", isTrue: true, level: 10 },
                { text: "The Planck length is the smallest measurable unit of length", isTrue: true, level: 10 }
            ];

            // Start game immediately
            startGame();

            function startGame() {
                score = 0;
                lives = 3;
                currentLevel = 1;
                gameActive = true;
                questionsAnsweredInLevel = 0;
                // Create a deep copy of statements array to work with
                availableStatements = JSON.parse(JSON.stringify(statements));
                scoreDisplay.textContent = '0';
                livesDisplay.textContent = '❤️'.repeat(lives);
                updateLevel();
                showNextStatement();
            }

            function updateLevel() {
                if (currentLevel === 10) {
                    levelDisplay.textContent = `Level ${currentLevel} - Final Level!`;
                } else {
                    levelDisplay.textContent = `Level ${currentLevel} (${questionsAnsweredInLevel}/${questionsPerLevel})`;
                }
            }

            function showNextStatement() {
                if (!gameActive) return;

                // Clear any existing timer
                if (currentTimer) {
                    clearTimeout(currentTimer);
                }

                // Reset answer lock
                answerLocked = false;

                // Filter statements for current level
                let currentLevelStatements = availableStatements.filter(s => s.level === currentLevel);

                // If we're on level 10 and no more statements, or below level 10 and completed questions per level
                if ((currentLevel === 10 && currentLevelStatements.length === 0) || 
                    (currentLevel < 10 && questionsAnsweredInLevel >= questionsPerLevel)) {
                    if (currentLevel === 10) {
                        // Double the score for completing all level 10 questions!
                        score *= 2;
                        scoreDisplay.textContent = score;
                        endGame();
                        return;
                    } else {
                        // Move to next level
                        currentLevel++;
                        questionsAnsweredInLevel = 0;
                        updateLevel();
                        showNextStatement();
                        return;
                    }
                }

                if (currentLevelStatements.length === 0) {
                    // No more questions available at this level
                    endGame();
                    return;
                }

                // Select random statement from available ones
                const randomIndex = Math.floor(Math.random() * currentLevelStatements.length);
                currentStatement = currentLevelStatements[randomIndex];
                
                // Remove the selected statement from available statements
                availableStatements = availableStatements.filter(s => s.text !== currentStatement.text);
                
                statement.textContent = currentStatement.text;

                // Reset and start timer
                timerBar.style.transition = 'none';
                timerBar.style.transform = 'scaleX(1)';
                setTimeout(() => {
                    timerBar.style.transition = `transform ${getTimeForLevel()}s linear`;
                    timerBar.style.transform = 'scaleX(0)';
                }, 50);

                // Set timeout for statement
                currentTimer = setTimeout(() => {
                    if (gameActive && !answerLocked) {
                        handleAnswer(null); // Time's up!
                    }
                }, getTimeForLevel() * 1000);
            }

            function getTimeForLevel() {
                if (currentLevel <= 3) return 3;
                if (currentLevel <= 6) return 2;
                return 1;
            }

            function handleAnswer(isTrue) {
                if (!gameActive || answerLocked) return;
                
                // Lock answer handling to prevent multiple answers
                answerLocked = true;

                // Clear current timer
                if (currentTimer) {
                    clearTimeout(currentTimer);
                }

                const isCorrect = isTrue === currentStatement.isTrue;
                
                if (isCorrect) {
                    score += 1;
                    gameContainer.classList.add('correct-flash');
                    setTimeout(() => gameContainer.classList.remove('correct-flash'), 500);
                } else {
                    lives--;
                    livesDisplay.textContent = '❤️'.repeat(lives);
                    gameContainer.classList.add('wrong-flash');
                    setTimeout(() => gameContainer.classList.remove('wrong-flash'), 500);
                }

                scoreDisplay.textContent = score;
                questionsAnsweredInLevel++;
                updateLevel();

                // Check game over conditions
                if (lives <= 0) {
                    endGame();
                } else {
                    // Short delay before next question
                    setTimeout(showNextStatement, 500);
                }
            }

            function endGame() {
                gameActive = false;
                loading.classList.remove('hidden');
                document.getElementById('finalScore').value = score;
                scoreForm.submit();
            }

            // Button click handlers
            trueBtn.addEventListener('click', () => handleAnswer(true));
            falseBtn.addEventListener('click', () => handleAnswer(false));

            // Keyboard controls
            document.addEventListener('keydown', (e) => {
                if (!gameActive || answerLocked) return;
                if (e.key === 'ArrowLeft') handleAnswer(true);
                if (e.key === 'ArrowRight') handleAnswer(false);
            });
        });
    </script>
    @endpush
</x-app-layout> 