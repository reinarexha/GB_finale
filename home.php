<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/app/models/PageContent.php';
require_once __DIR__ . '/app/models/SliderItem.php';

function e(string $value): string {
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function sliderImageUrl(string $path): string {
  $path = trim($path);
  if ($path === '') return '';

  if (preg_match('~^https?://~i', $path)) {
    return $path;
  }

  $base = rtrim(BASE_URL, '/');
  $normalized = '/' . ltrim(str_replace('\\', '/', $path), '/');

  if ($base !== '' && str_starts_with($normalized, $base . '/')) {
    return $normalized;
  }

  if (str_starts_with($normalized, UPLOADS_NEWS_WEB . '/')) {
    return $base . $normalized;
  }

  if (str_starts_with($normalized, '/img/')) {
    return $base . $normalized;
  }

  return $base . UPLOADS_NEWS_WEB . '/' . basename($normalized);
}

$content = new PageContent();
$heroTitle = $content->getText('home', 'hero_title', 'Welcome to Gamebits');
$heroSubtitle = $content->getText('home', 'hero_subtitle', 'Learn leadership through play.');
$slides = SliderItem::getAll();

$pageTitle = 'Home';
$currentPage = 'home';
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <h1 class="hero-title"><?= e($heroTitle) ?></h1>
  <p class="hero-sub"><?= e($heroSubtitle) ?></p>
  <div class="hero-buttons">
    <a href="<?= e(rtrim(BASE_URL, '/')) ?>/games.php" class="btn-primary">Play Mini-Games</a>
    <a href="<?= rtrim(BASE_URL,'/') . '/about.php' ?>" class="btn-primary">Learn More</a>
  </div>
</section>

<?php if (!empty($slides)): ?>
<section class="featured-games" style="padding-top: 0;">
  <h2 class="section-title">Featured Highlights</h2>

  <!-- student project: keeping it simple, homepage content and slides come from DB only. -->
  <div class="slider" tabindex="0" style="max-width: 960px; margin: 0 auto 2rem; border-radius: 12px; border: 1px solid #d1d5db; background: #fff;">
    <button class="slider-btn prev" type="button" aria-label="Previous slide">‹</button>
    <button class="slider-btn next" type="button" aria-label="Next slide">›</button>

    <div class="slider-dots" aria-label="Slider dots"></div>

    <?php foreach ($slides as $slide): ?>
      <?php $imageUrl = sliderImageUrl((string)$slide->image_path); ?>
      <div class="slide" style="padding: 1rem;">
        <div style="display:grid;grid-template-columns:1fr;gap:12px;align-items:center;">
          <?php if ($imageUrl !== ''): ?>
            <img
              src="<?= e($imageUrl) ?>"
              alt="<?= e($slide->title ?: 'Slide image') ?>"
              class="slider-image"
            >
          <?php else: ?>
            <div class="slider-empty">Image missing for this slide.</div>
          <?php endif; ?>
          <div>
            <h3 style="margin:0 0 6px;"><?= e($slide->title) ?></h3>
            <?php if (!empty($slide->subtitle)): ?>
              <p style="margin:0;opacity:.85;"><?= e((string)$slide->subtitle) ?></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php else: ?>
<section class="featured-games" style="padding-top: 0;">
  <h2 class="section-title">Featured Highlights</h2>
  <p class="slider-empty">No slider highlights yet. Add slides from Admin.</p>
</section>
<?php endif; ?>

<section class="featured-games">
  <h2 class="section-title">Featured Games</h2>
  <div class="games-grid">
    <div class="game-card">
      <h3 class="game-title">Sudoku</h3>
      <p class="game-desc">Boost logic and focus with the classic number puzzle.</p>
      <a href="<?= e(rtrim(BASE_URL, '/')) ?>/games/sudoku/sudoku.php" class="btn-primary">Play Now</a>
    </div>

    <div class="game-card">
      <h3 class="game-title">Blackjack</h3>
      <p class="game-desc">Practice decision making and probability in this card classic.</p>
      <a href="<?= e(rtrim(BASE_URL, '/')) ?>/games/blackjack/blackjack.php" class="btn-primary">Play Now</a>
    </div>

    <div class="game-card">
      <h3 class="game-title">Snake</h3>
      <p class="game-desc">Sharpen reaction time as you guide the growing snake.</p>
      <a href="<?= e(rtrim(BASE_URL, '/')) ?>/games/snake/snake.php" class="btn-primary">Play Now</a>
    </div>
  </div>
</section>

<script src="<?= e(rtrim(BASE_URL, '/')) ?>/public/assets/js/slider.js"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>

