<?php
declare(strict_types=1);

namespace App\Core;

final class Session
{
    public function __construct(array $cfg = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name($cfg['name'] ?? 'fsess');
            session_start();
        }
    }

    public function get(string $key, mixed $default = null): mixed { return $_SESSION[$key] ?? $default; }
    public function set(string $key, mixed $value): void { $_SESSION[$key] = $value; }
    public function forget(string $key): void            { unset($_SESSION[$key]); }
    public function regenerate(): void                   { session_regenerate_id(true); }
    public function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}