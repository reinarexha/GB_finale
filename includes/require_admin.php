<?php
require_once __DIR__ . '/bootstrap.php';

$auth->requireLogin();  // redirects if not logged in
$auth->requireAdmin();  // 403 if not admin



