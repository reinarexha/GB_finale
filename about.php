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
</section>

<section class="featured-games" style="padding-top: 10px;">
  <div style="max-width: 900px; margin: 0 auto; padding: 0 16px 24px;">
    <div style="background:#fff;border-radius:12px;box-shadow:0 4px 14px rgba(0,0,0,.08);padding:18px;">
      <h2 class="section-title" style="margin-top:0;">Team</h2>
      <p style="margin: 0 0 12px;">We are a student team focused on making learning through games simple and practical.</p>
      <ul style="margin:0 0 16px 18px; padding:0;">
        <li>Reina Rexha - Product and UX</li>
        <li>Eliza Kaqiu - Content and Testing</li>
        <li>Dalina Baraliu - Development</li>
      </ul>
      <h3 style="margin-bottom:8px;">What We Built</h3>
      <p style="margin:0;">A homepage, game pages, leaderboard/news pages, login/auth pages, and a basic admin area for managing games and news.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
