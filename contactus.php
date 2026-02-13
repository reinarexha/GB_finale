<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Contact';
$currentPage = '';
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <h1 class="hero-title">Contact Us</h1>
  <p class="hero-sub">Questions or feedback? Send us a message below.</p>
</section>

<section class="featured-games" style="padding-top: 10px;">
  <div style="max-width: 760px; margin: 0 auto; padding: 0 16px 24px;">
    <div style="background:#fff;border-radius:12px;box-shadow:0 4px 14px rgba(0,0,0,.08);padding:18px;">
      <form method="post" action="#">
        <div style="display:grid;gap:12px;">
          <label>
            Name
            <input type="text" name="name" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
          </label>

          <label>
            Email
            <input type="email" name="email" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
          </label>

          <label>
            Subject
            <input type="text" name="subject" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
          </label>

          <label>
            Message
            <textarea name="message" rows="5" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;"></textarea>
          </label>

          <button type="submit" class="btn-primary" style="width:fit-content;">Send Message</button>
        </div>
      </form>
      <p style="margin-top:12px;font-size:.95rem;opacity:.8;">This is a demo contact form for the student project UI.</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
