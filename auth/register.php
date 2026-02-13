<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$pdo = Database::getConnection();

if ($auth->check()) {
  header('Location: ' . rtrim(BASE_URL, '/') . '/games.php');
  exit;
}

$error = '';
$usernameValue = '';
$emailValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $usernameValue = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
  $emailValue = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

  if ($username === '' || $email === '' || $password === '') {
    $error = 'Please fill in all fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Please enter a valid email address.';
  } elseif (strlen($password) < 6) {
    $error = 'Password must be at least 6 characters.';
  } else {
    try {
      // ensure unique username/email
      $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
      $stmt->execute([$username, $email]);
      $exists = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($exists) {
        $error = 'Username or email already in use.';
      } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
          INSERT INTO users (username, email, password, role)
          VALUES (?, ?, ?, 'user')
        ");
        $stmt->execute([$username, $email, $hash]);

        $newUserId = (int)$pdo->lastInsertId();

        session_regenerate_id(true);
        $auth->login([
          'id' => $newUserId,
          'username' => $username,
          'role' => 'user',
        ]);

        header('Location: ' . rtrim(BASE_URL, '/') . '/games.php');
        exit;
      }
    } catch (Exception $e) {
      $error = 'Something went wrong. Please try again.';
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= rtrim(BASE_URL, '/') ?>/css/styles.css">
</head>
<body>
  <div class="signup-wrapper">
    <div class="signup-card">
      <h1>Register</h1>

      <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="POST" action="<?= rtrim(BASE_URL, '/') ?>/auth/register.php">
        <label for="username">Username</label>
        <input class="form-input" id="username" name="username" required value="<?= $usernameValue ?>">

        <label for="email">Email</label>
        <input class="form-input" id="email" name="email" type="email" required value="<?= $emailValue ?>">

        <label for="password">Password</label>
        <input class="form-input" id="password" name="password" type="password" required>

        <button class="btn-primary" type="submit">Create account</button>
      </form>

      <div class="login-text">
        <p>Already have an account? <a href="<?= rtrim(BASE_URL, '/') ?>/auth/login.php">Log in</a></p>
      </div>
    </div>
  </div>
</body>
</html>

