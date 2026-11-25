<?php
declare(strict_types=1);

namespace App\Core;

final class Response
{
    private int $status = 200;
    private array $headers = ['Content-Type' => 'text/html; charset=utf-8'];
    private string $body = '';

    public function status(int $s): self { $this->status = $s; return $this; }
    public function header(string $k, string $v): self { $this->headers[$k] = $v; return $this; }
    public function html(string $body): self { $this->body = $body; return $this; }

    public function redirect(string $to, int $code = 302): never
    {
        http_response_code($code);
        header('Location: ' . $to);
        exit;
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $k => $v) header("$k: $v");
        echo $this->body;
    }
}