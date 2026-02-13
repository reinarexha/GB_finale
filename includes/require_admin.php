<?php
// includes/require_admin.php
require_once __DIR__ . '/../app/core/Auth.php';

// $auth = new Auth();
$auth->requireLogin();  // redirects if not logged in
$auth->requireAdmin();  // 403 if not admin


