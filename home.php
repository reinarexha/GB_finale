<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/app/models/PageContent.php';
require_once __DIR__ . '/app/models/SliderItem.php';

function e(string $value): string {
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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
<section class="featured-games featured-compact">
  <h2 class="section-title">Featured Highlights</h2>

  <!-- student project: keeping it simple, homepage content and slides come from DB only. -->
  <div class="slider slider-shell" tabindex="0">
    <button class="slider-btn prev" type="button" aria-label="Previous slide">‹</button>
    <button class="slider-btn next" type="button" aria-label="Next slide">›</button>

    <div class="slider-dots" aria-label="Slider dots"></div>

    <?php foreach ($slides as $slide): ?>
      <?php $imageUrl = image_url((string)$slide->image_path); ?>
      <div class="slide slide-pad">
        <div class="slide-content">
          <div class="slider-media">
            <?php if ($imageUrl !== ''): ?>
              <img
                src="<?= e($imageUrl) ?>"
                alt="<?= e($slide->title ?: 'Slide image') ?>"
                class="slider-image"
              >
            <?php else: ?>
              <div class="slider-empty">Image missing for this slide.</div>
            <?php endif; ?>
          </div>
          <div>
            <h3 class="slide-title"><?= e($slide->title) ?></h3>
            <?php if (!empty($slide->subtitle)): ?>
              <p class="slide-sub"><?= e((string)$slide->subtitle) ?></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php else: ?>
<section class="featured-games featured-compact">
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


