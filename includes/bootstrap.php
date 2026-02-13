<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once BASE_PATH . '/app/core/Database.php';
require_once BASE_PATH . '/app/core/Auth.php';

$auth = new Auth();
$auth->start();

require_once __DIR__ . '/csrf.php';
