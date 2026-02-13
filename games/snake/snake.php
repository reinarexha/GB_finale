<?php
require_once __DIR__ . '/../../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = 'Snake';
$currentPage = 'games';
include __DIR__ . '/../../includes/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/games/snake/snake.css">

<div id="gameContainer">
  <h2>Snake</h2>
  <canvas id="gameBoard" width="500" height="500"></canvas>
  <div id="scoreWrap">Score: <span id="scoreText">0</span></div>
  <p id="statusText">Use arrow keys to move.</p>
  <button id="resetBtn">Reset</button>
</div>

<script>
  window.GAMEBITS = {
    baseUrl: "<?= BASE_URL ?>",
    game: "snake"
  };
</script>

<script src="<?= BASE_URL ?>/games/snake/snake.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
