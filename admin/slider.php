<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/require_admin.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/models/SliderItem.php';

$pdo = Database::getConnection();
$errors = [];
$success = '';

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function old(string $key, string $default = ''): string {
  return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
}

function getContentText(PDO $pdo, string $page, string $sectionKey, string $default = ''): string {
  $stmt = $pdo->prepare('SELECT content_text FROM page_contents WHERE page = :page AND section_key = :section_key LIMIT 1');
  $stmt->execute([
    ':page' => $page,
    ':section_key' => $sectionKey,
  ]);

  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row || !isset($row['content_text'])) {
    return $default;
  }

  return (string)$row['content_text'];
}

function saveContentText(PDO $pdo, string $page, string $sectionKey, string $text): bool {
  $stmt = $pdo->prepare(
    'INSERT INTO page_contents (page, section_key, content_text)
     VALUES (:page, :section_key, :content_text)
     ON DUPLICATE KEY UPDATE content_text = VALUES(content_text)'
  );

  return $stmt->execute([
    ':page' => $page,
    ':section_key' => $sectionKey,
    ':content_text' => $text,
  ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $postedToken = (string)($_POST['csrf_token'] ?? '');
  if (!hash_equals($_SESSION['csrf_token'], $postedToken)) {
    $errors[] = 'Invalid form token. Please refresh and try again.';
  } else {
    if (isset($_POST['save_home'])) {
      $heroTitle = trim((string)($_POST['hero_title'] ?? ''));
      $heroSubtitle = trim((string)($_POST['hero_subtitle'] ?? ''));

      if ($heroTitle === '') {
        $errors[] = 'Hero title is required.';
      }

      if (empty($errors)) {
        // student project: keeping it simple, DB is the single source for homepage content + slider.
        $ok1 = saveContentText($pdo, 'home', 'hero_title', $heroTitle);
        $ok2 = saveContentText($pdo, 'home', 'hero_subtitle', $heroSubtitle);

        if ($ok1 && $ok2) {
          header('Location: ' . BASE_URL . '/admin/slider.php?success=Home content saved');
          exit;
        }

        $errors[] = 'Could not save home content.';
      }
    }

    if (isset($_POST['save_about'])) {
      $aboutText = trim((string)($_POST['about_text'] ?? ''));

      if ($aboutText === '') {
        $errors[] = 'About text is required.';
      }

      if (empty($errors)) {
        if (saveContentText($pdo, 'about', 'section_1', $aboutText)) {
          header('Location: ' . BASE_URL . '/admin/slider.php?success=About content saved');
          exit;
        }
        $errors[] = 'Could not save about content.';
      }
    }

    if (isset($_POST['add_slide'])) {
      $title = trim((string)($_POST['title'] ?? ''));
      $subtitle = trim((string)($_POST['subtitle'] ?? ''));
      $imagePath = trim((string)($_POST['image_path'] ?? ''));

      if ($title === '') {
        $errors[] = 'Slide title is required.';
      }
      if ($imagePath === '') {
        $errors[] = 'Slide image path is required.';
      }
      if ($imagePath !== '' && $imagePath[0] !== '/') {
        $errors[] = 'Image path must start with /. Example: /img/slide.jpg';
      }

      if (empty($errors)) {
        $item = new SliderItem();
        $item->title = $title;
        $item->subtitle = $subtitle !== '' ? $subtitle : null;
        $item->image_path = $imagePath;

        if ($item->save()) {
          header('Location: ' . BASE_URL . '/admin/slider.php?success=Slide added');
          exit;
        }
        $errors[] = 'Could not save slide.';
      }
    }

    if (isset($_POST['delete']) && isset($_POST['id'])) {
      $id = (int)($_POST['id'] ?? 0);
      if ($id > 0 && SliderItem::delete($id)) {
        header('Location: ' . BASE_URL . '/admin/slider.php?success=Slide deleted');
        exit;
      }
      $errors[] = 'Could not delete slide.';
    }
  }
}

if (isset($_GET['success'])) {
  $success = (string)$_GET['success'];
}

$heroTitleVal = htmlspecialchars(old('hero_title', getContentText($pdo, 'home', 'hero_title', 'Welcome to Gamebits')), ENT_QUOTES, 'UTF-8');
$heroSubtitleVal = htmlspecialchars(old('hero_subtitle', getContentText($pdo, 'home', 'hero_subtitle', 'Learn leadership through play.')), ENT_QUOTES, 'UTF-8');
$aboutTextVal = htmlspecialchars(old('about_text', getContentText($pdo, 'about', 'section_1', 'About text here...')), ENT_QUOTES, 'UTF-8');
$items = SliderItem::getAll();

$pageTitle = 'Manage Slider';
$currentPage = 'slider';
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero admin-hero">
  <h1 class="hero-title">Manage Slider</h1>
  <p class="hero-sub">Edit homepage hero text and slider items.</p>
</section>

<section class="admin-wrap admin-form-wrap">

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul class="admin-errors">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="admin-card admin-form-card">
    <h2 class="section-title" style="margin: 0 0 1rem;">Home page content</h2>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

      <div class="admin-form-group">
        <label class="admin-label">Hero title</label>
        <input class="admin-input" type="text" name="hero_title" value="<?= $heroTitleVal ?>">
      </div>
      <div class="admin-form-group">
        <label class="admin-label">Hero subtitle</label>
        <input class="admin-input" type="text" name="hero_subtitle" value="<?= $heroSubtitleVal ?>">
      </div>
      <div class="admin-form-actions">
        <button class="btn-primary" type="submit" name="save_home" value="1">Save Home</button>
      </div>
    </form>
  </div>

  <div class="admin-card admin-form-card">
    <h2 class="section-title" style="margin: 0 0 1rem;">About page content</h2>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

      <div class="admin-form-group">
        <label class="admin-label">About text</label>
        <textarea class="admin-input" name="about_text" rows="5"><?= $aboutTextVal ?></textarea>
      </div>
      <div class="admin-form-actions">
        <button class="btn-primary" type="submit" name="save_about" value="1">Save About</button>
      </div>
    </form>
  </div>

  <div class="admin-card admin-form-card">
    <h2 class="section-title" style="margin: 0 0 1rem;">Add new slide</h2>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

      <div class="admin-form-group">
        <label class="admin-label">Slide title</label>
        <input class="admin-input" type="text" name="title" value="<?= htmlspecialchars(old('title')) ?>">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Slide subtitle</label>
        <input class="admin-input" type="text" name="subtitle" value="<?= htmlspecialchars(old('subtitle')) ?>">
      </div>

      <div class="admin-form-group">
        <label class="admin-label">Image path</label>
        <input class="admin-input" type="text" name="image_path" placeholder="/img/slide.jpg" value="<?= htmlspecialchars(old('image_path')) ?>">
      </div>

      <div class="admin-form-actions">
        <button class="btn-primary" type="submit" name="add_slide" value="1">Add Slide</button>
      </div>
    </form>
  </div>

  <div class="admin-card admin-form-card">
    <h2 class="section-title" style="margin: 0 0 1rem;">Current slides</h2>

    <?php if (empty($items)): ?>
      <p>No slides yet.</p>
    <?php else: ?>
      <?php foreach ($items as $item): ?>
        <div style="border:1px solid #d1d5db;border-radius:10px;padding:10px;margin-bottom:10px;display:flex;justify-content:space-between;gap:10px;align-items:center;">
          <div>
            <strong><?= htmlspecialchars($item->title) ?></strong><br>
            <small><?= htmlspecialchars((string)$item->subtitle) ?></small><br>
            <small><?= htmlspecialchars($item->image_path) ?></small>
          </div>
          <form method="post" onsubmit="return confirm('Delete this slide?');">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="id" value="<?= (int)$item->id ?>">
            <button class="btn-secondary" type="submit" name="delete" value="1">Delete</button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</section>

<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
