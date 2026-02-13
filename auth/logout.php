<?php
require_once __DIR__ . '/../app/core/Auth.php';

$auth = new Auth();
$auth->logout();

header('Location: /auth/login.php');
exit;

