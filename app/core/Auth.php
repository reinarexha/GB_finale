<?php

declare(strict_types=1);

class Auth
{
    
    private const SESSION_KEY = 'auth_user';

    public function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    
    public function login(array $user): void
    {
        $this->start();

        $id = $user['id'] ?? $user['user_id'] ?? null;
        if ($id === null) {
            return;
        }

        $_SESSION[self::SESSION_KEY] = [
            'user_id'  => (int) $id,
            'username' => $user['username'] ?? '',
            'role'     => $user['role'] ?? 'user',
        ];
    }


    public function logout(): void
    {
        $this->start();

        unset($_SESSION[self::SESSION_KEY]);

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }


    public function check(): bool
    {
        $this->start();
        return isset($_SESSION[self::SESSION_KEY]) && is_array($_SESSION[self::SESSION_KEY]);
    }

   
    public function id(): ?int
    {
        $user = $this->user();
        return $user !== null ? (int) $user['user_id'] : null;
    }

    
    public function user(): ?array
    {
        $this->start();
        $data = $_SESSION[self::SESSION_KEY] ?? null;
        return is_array($data) ? $data : null;
    }

   
    public function isAdmin(): bool
    {
        $u = $this->user();
        return $u !== null && isset($u['role']) && $u['role'] === 'admin';
    }

   
   public function requireLogin(): void
{
    if (!$this->check()) {
        $base = defined('BASE_URL') ? rtrim((string)BASE_URL, '/') : '';
        header('Location: ' . $base . '/auth/login.php?error=login_required');
        exit;
    }
}



    
    public function requireAdmin(): void
{
    if (!$this->isAdmin()) {
        $base = defined('BASE_URL') ? rtrim((string)BASE_URL, '/') : '';
        header('Location: ' . $base . '/auth/login.php?error=unauthorized');
        exit;
    }
}

}
