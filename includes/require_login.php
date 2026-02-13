<?php
require_once __DIR__ . '/../app/core/Auth.php';

$auth = new Auth();
$auth->requireLogin();

