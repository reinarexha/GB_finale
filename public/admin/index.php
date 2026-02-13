<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/bootstrap.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Deprecated Admin Path</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; line-height: 1.4; }
    .box { max-width: 700px; padding: 16px; border: 1px solid #ccc; border-radius: 8px; }
  </style>
</head>
<body>
  <div class="box">
    <h1>Deprecated / Not in use</h1>
    <p>This admin path is no longer used.</p>
    <p>Please use the active admin dashboard at <a href="<?= rtrim(BASE_URL, '/') ?>/admin/dashboard.php"><?= rtrim(BASE_URL, '/') ?>/admin/dashboard.php</a>.</p>
  </div>
</body>
</html>
