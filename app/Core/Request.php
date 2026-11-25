<?php
declare(strict_types=1);

namespace App\Core;

final class Request
{
    public function __construct(
        private array $get,
        private array $post,
        private array $server,
        private array $cookies,
        private array $files
    ) {}

    public static function fromGlobals(): self
    {
        return new self($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES);
    }

    public function method(): string { return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET'); }

    public function path(): string
    {
        $uri = parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $p = '/' . trim($uri, '/');
        return $p === '/' ? '/' : rtrim($p, '/');
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array  { return array_merge($this->get, $this->post); }
    public function post(): array { return $this->post; }
    public function get(): array  { return $this->get; }
    public function ip(): string  { return $this->server['REMOTE_ADDR'] ?? ''; }
    public function userAgent(): string { return substr($this->server['HTTP_USER_AGENT'] ?? '', 0, 255); }
    public function isPost(): bool { return $this->method() === 'POST'; }
}