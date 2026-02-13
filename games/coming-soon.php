<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$title = trim((string)($_GET['title'] ?? 'This game'));

$pageTitle = 'Coming Soon';
$currentPage = 'games';
include __DIR__ . '/../includes/header.php';
?>

<section class="hero">
  <h1 class="hero-title">Coming Soon</h1>
  <p class="hero-sub"><?= e($title) ?> is not available yet. We are still building it.</p>
  <div class="hero-buttons">
    <a href="<?= e(rtrim(BASE_URL, '/')) ?>/games.php" class="btn-primary">Back to Games</a>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
