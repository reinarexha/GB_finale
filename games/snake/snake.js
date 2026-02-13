const gameBoard = document.querySelector("#gameBoard");
const ctx = gameBoard.getContext("2d");
const scoreText = document.querySelector("#scoreText");
const resetBtn = document.querySelector("#resetBtn");
const statusText = document.querySelector("#statusText");

const gameWidth = gameBoard.width;
const gameHeight = gameBoard.height;
const unitSize = 25;

const boardBackground = "#ffffff";
const snakeColor = "#6ee7b7";
const snakeBorder = "#111827";
const foodColor = "#ef4444";

let running = false;
let xVelocity = unitSize;
let yVelocity = 0;
let foodX = 0;
let foodY = 0;
let score = 0;
let timerId = null;
let scoreSubmitted = false;

let snake = [];

window.addEventListener("keydown", changeDirection);
resetBtn.addEventListener("click", resetGame);

startGame();

function startGame() {
  running = true;
  score = 0;
  scoreSubmitted = false;
  xVelocity = unitSize;
  yVelocity = 0;
  snake = [
    { x: unitSize * 4, y: 0 },
    { x: unitSize * 3, y: 0 },
    { x: unitSize * 2, y: 0 },
    { x: unitSize, y: 0 },
    { x: 0, y: 0 }
  ];

  scoreText.textContent = String(score);
  if (statusText) statusText.textContent = "Use arrow keys to move.";

  createFood();
  drawGame();
}

function drawGame() {
  if (!running) {
    return;
  }

  timerId = setTimeout(() => {
    clearBoard();
    drawFood();
    moveSnake();
    drawSnake();
    checkGameOver();
    drawGame();
  }, 120);
}

function clearBoard() {
  ctx.fillStyle = boardBackground;
  ctx.fillRect(0, 0, gameWidth, gameHeight);
}

function createFood() {
  const randomFood = (max) => {
    const min = 0;
    return Math.round((Math.random() * (max - min) + min) / unitSize) * unitSize;
  };

  let valid = false;
  while (!valid) {
    foodX = randomFood(gameWidth - unitSize);
    foodY = randomFood(gameHeight - unitSize);
    valid = !snake.some((segment) => segment.x === foodX && segment.y === foodY);
  }
}

function drawFood() {
  ctx.fillStyle = foodColor;
  ctx.fillRect(foodX, foodY, unitSize, unitSize);
}

function moveSnake() {
  const head = { x: snake[0].x + xVelocity, y: snake[0].y + yVelocity };
  snake.unshift(head);

  if (head.x === foodX && head.y === foodY) {
    score += 1;
    scoreText.textContent = String(score);
    createFood();
  } else {
    snake.pop();
  }
}

function drawSnake() {
  ctx.fillStyle = snakeColor;
  ctx.strokeStyle = snakeBorder;
  for (const segment of snake) {
    ctx.fillRect(segment.x, segment.y, unitSize, unitSize);
    ctx.strokeRect(segment.x, segment.y, unitSize, unitSize);
  }
}

function changeDirection(event) {
  const keyPressed = event.keyCode;

  const LEFT = 37;
  const UP = 38;
  const RIGHT = 39;
  const DOWN = 40;

  const goingUp = yVelocity === -unitSize;
  const goingDown = yVelocity === unitSize;
  const goingRight = xVelocity === unitSize;
  const goingLeft = xVelocity === -unitSize;

  if (keyPressed === LEFT && !goingRight) {
    xVelocity = -unitSize;
    yVelocity = 0;
  } else if (keyPressed === UP && !goingDown) {
    xVelocity = 0;
    yVelocity = -unitSize;
  } else if (keyPressed === RIGHT && !goingLeft) {
    xVelocity = unitSize;
    yVelocity = 0;
  } else if (keyPressed === DOWN && !goingUp) {
    xVelocity = 0;
    yVelocity = unitSize;
  }
}

function checkGameOver() {
  const head = snake[0];

  if (
    head.x < 0 ||
    head.x >= gameWidth ||
    head.y < 0 ||
    head.y >= gameHeight
  ) {
    endGame();
    return;
  }

  for (let i = 1; i < snake.length; i += 1) {
    if (head.x === snake[i].x && head.y === snake[i].y) {
      endGame();
      return;
    }
  }
}

function endGame() {
  running = false;
  if (timerId) {
    clearTimeout(timerId);
  }

  if (statusText) {
    statusText.textContent = `Game Over. Final score: ${score}. Press Reset to play again.`;
  }

  if (!scoreSubmitted) {
    scoreSubmitted = true;
    submitScore(score);
  }
}

function resetGame() {
  if (timerId) {
    clearTimeout(timerId);
  }
  startGame();
}

async function submitScore(finalScore) {
  try {
    const baseUrl = window.GAMEBITS?.baseUrl ?? "";
    const gameKey = window.GAMEBITS?.game ?? "snake";

    const res = await fetch(`${baseUrl}/games/submit_score.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ game: gameKey, score: finalScore })
    });

    const data = await res.json();
    if (!data.ok) {
      console.warn("Score not saved:", data.error);
    }
  } catch (e) {
    console.warn("Score submit failed:", e);
  }
}
