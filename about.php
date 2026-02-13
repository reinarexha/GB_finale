<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'About';
$currentPage = 'about';
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <h1 class="hero-title">About This Project</h1>
  <p class="hero-sub">
    Gamebits is a small university project by students learning web development.
    The goal is to build short mini-games and simple admin tools in one platform.
  </p>
  <div class="hero-buttons">
    <a href="<?= BASE_URL ?>/contactus.php" class="btn-primary">Contact Us</a>
  </div>
</section>

<section class="about-section">
  <div class="about-container">
    <h2 class="section-title">Team and Project</h2>

    <div class="team-grid">
      <article class="team-card">
        <h3>Team</h3>
        <p>We are a student team focused on making learning through games simple and practical.</p>
        <p>Reina Rexha - Product and UX</p>
        <p>Eliza Kaqiu - Content and Testing</p>
        <p>Dalina Baraliu - Development</p>
      </article>

      <article class="team-card">
        <h3>What We Built</h3>
        <p>
          A homepage, game pages, leaderboard and news pages, login and auth pages,
          and a basic admin area for managing games and news.
        </p>
      </article>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
