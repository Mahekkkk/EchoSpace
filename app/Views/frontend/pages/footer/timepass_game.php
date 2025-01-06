<?= $this->extend('frontend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        text-align: center;
        user-select: none;
    }

    .container {
        margin: 0 auto;
        max-width: 1200px;
    }

    canvas {
        display: block;
        margin: 20px auto;
        border: 2px solid #FF5722;
        border-radius: 10px;
        background: url('https://i.ibb.co/Q9yv5Jk/flappy-bird-set.png') 0 -340px repeat-x;
        background-size: cover;
    }

    h1 {
        font-size: 3rem;
        color: #FF5722;
        text-shadow: 2px 2px #ddd;
    }

    button {
        padding: 10px 20px;
        font-size: 1.2rem;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    #scoreBoard {
        font-size: 1.2rem;
        color: #333;
        margin-top: 10px;
    }
</style>


    <div class="container mt-4">
        <h1>TimePass Game: Flappy Bird</h1>
        <p style="font-size: 1.5rem; color: #333; margin-bottom: 20px;">
            Click "Start Game" to begin. Click anywhere on the canvas to make the bird fly. Avoid the pipes!
        </p>
        <button id="startButton">Start Game</button>
        <canvas id="flappyBirdGame" width="900" height="500" style="display: none;"></canvas>
        <p id="scoreBoard" style="display: none;">Score: <span id="score">0</span> | High Score: <span id="highScore">0</span></p>
    </div>


<script>
    const canvas = document.getElementById('flappyBirdGame');
    const ctx = canvas.getContext('2d');
    const startButton = document.getElementById('startButton');
    const scoreBoard = document.getElementById('scoreBoard');
    const scoreDisplay = document.getElementById('score');
    const highScoreDisplay = document.getElementById('highScore');

    let bird, pipes, pipeWidth, pipeGap, score, highScore, gameRunning, gameOver, animationFrame;

    // Initialize game settings
    function initializeGame() {
        bird = { x: 100, y: 200, width: 30, height: 30, gravity: 0.1, lift: -3, velocity: -1 }; // Optimized bird controls
        pipes = [];
        pipeWidth = 60;
        pipeGap = 200;
        score = 0;
        gameRunning = true;
        gameOver = false;
        createPipe();
    }

    // Create pipes
    function createPipe() {
        const pipeY = Math.floor(Math.random() * (canvas.height - pipeGap - 50)) + 30;
        pipes.push({ x: canvas.width, y: pipeY });
    }

    // Draw the bird
    function drawBird() {
        ctx.fillStyle = '#FF5722';
        ctx.beginPath();
        ctx.arc(bird.x, bird.y, bird.width / 2, 0, Math.PI * 2);
        ctx.fill();
    }

    // Draw the pipes
    function drawPipes() {
        pipes.forEach(pipe => {
            ctx.fillStyle = '#4CAF50';
            ctx.fillRect(pipe.x, 0, pipeWidth, pipe.y);
            ctx.fillRect(pipe.x, pipe.y + pipeGap, pipeWidth, canvas.height - pipe.y - pipeGap);
        });
    }

    // Update pipes movement
    function updatePipes() {
        pipes.forEach(pipe => {
            pipe.x -= 3; // Adjusted pipe speed for landscape mode
            if (pipe.x + pipeWidth < 0) {
                pipes.shift();
                score++;
            }
        });

        if (pipes.length === 0 || pipes[pipes.length - 1].x < canvas.width / 2) {
            createPipe();
        }
    }

    // Check for collisions
    function checkCollision() {
        for (let pipe of pipes) {
            if (
                bird.x + bird.width / 2 > pipe.x &&
                bird.x - bird.width / 2 < pipe.x + pipeWidth &&
                (bird.y - bird.height / 2 < pipe.y || bird.y + bird.height / 2 > pipe.y + pipeGap)
            ) {
                gameRunning = false;
                gameOver = true;
                return;
            }
        }

        if (bird.y + bird.height / 2 >= canvas.height || bird.y - bird.height / 2 <= 0) {
            gameRunning = false;
            gameOver = true;
        }
    }

    // Update the score
    function updateScore() {
        scoreDisplay.textContent = score;
        if (score > highScore) {
            highScore = score;
            highScoreDisplay.textContent = highScore;
        }
    }

    // Main game loop
    function gameLoop() {
        if (!gameRunning) {
            ctx.font = '30px Arial';
            ctx.fillStyle = '#FF0000';
            ctx.fillText('Game Over! Click Start to Restart', canvas.width / 4, canvas.height / 2);
            return;
        }

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        bird.velocity += bird.gravity;
        bird.y += bird.velocity;

        drawBird();
        drawPipes();
        updatePipes();
        checkCollision();
        updateScore();

        ctx.font = '20px Arial';
        ctx.fillStyle = '#000';
        ctx.fillText('Score: ' + score, 10, 30);

        animationFrame = requestAnimationFrame(gameLoop);
    }

    // Start the game
    function startGame() {
        initializeGame();
        scoreBoard.style.display = 'block';
        canvas.style.display = 'block';
        startButton.style.display = 'none';
        gameLoop();
    }

    startButton.addEventListener('click', () => {
        startGame();
    });

    canvas.addEventListener('click', () => {
        if (gameRunning) {
            bird.velocity = bird.lift;
        } else if (gameOver) {
            cancelAnimationFrame(animationFrame);
            startGame();
        }
    });

    // High score persistence
    highScore = localStorage.getItem('highScore') || 0;
    highScoreDisplay.textContent = highScore;

    window.addEventListener('beforeunload', () => {
        localStorage.setItem('highScore', highScore);
    });
</script>

<?= $this->endSection() ?>
