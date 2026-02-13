<?php
require_once __DIR__ . '/bootstrap.php';

$auth->logout();

header('Location: ' . rtrim(BASE_URL, '/') . '/auth/login.php');
exit;



