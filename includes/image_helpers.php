<?php
declare(strict_types=1);

if (!function_exists('image_url')) {
    function image_url(string $path): string {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        // Normalize Windows-style separators before URL handling.
        $path = str_replace('\\', '/', $path);

        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }

        $path = preg_replace('~^\./+~', '', $path);

        $base = rtrim((string)BASE_URL, '/');
        if ($base !== '' && str_starts_with($path, $base . '/')) {
            return $path;
        }

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        return $base . $path;
    }
}
