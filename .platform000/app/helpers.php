<?php
declare(strict_types=1);

use App\Core\App;

if (!function_exists('t')) {
    function t(string $key, array $replace = []): string
    {
        $s = App::i18n()->dict()[$key] ?? $key;
        foreach ($replace as $k => $v) $s = str_replace(':' . $k, (string)$v, $s);
        return $s;
    }
}

if (!function_exists('e')) {
    function e(?string $s): string
    {
        return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('lang')) {
    function lang(): string { return App::i18n()->current(); }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        return $_SESSION['_csrf'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('flash')) {
    function flash(string $key, ?string $value = null): ?string
    {
        if ($value !== null) { $_SESSION['_flash'][$key] = $value; return null; }
        $v = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $v;
    }
}