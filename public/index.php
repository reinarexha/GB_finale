<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

header('Location: ' . rtrim(BASE_URL, '/') . '/home.php', true, 302);
exit;
