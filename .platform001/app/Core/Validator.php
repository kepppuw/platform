<?php
declare(strict_types=1);

namespace App\Core;

final class Validator
{
    private array $errors = [];

    public function __construct(private array $data) {}

    public function required(string $f, string $msg = 'Required'): self
    {
        if (!isset($this->data[$f]) || trim((string)$this->data[$f]) === '') $this->errors[$f] = $msg;
        return $this;
    }

    public function min(string $f, int $len, string $msg = 'Too short'): self
    {
        if (isset($this->data[$f]) && mb_strlen(trim((string)$this->data[$f])) < $len) $this->errors[$f] = $msg;
        return $this;
    }

    public function max(string $f, int $len, string $msg = 'Too long'): self
    {
        if (isset($this->data[$f]) && mb_strlen((string)$this->data[$f]) > $len) $this->errors[$f] = $msg;
        return $this;
    }

    public function email(string $f, string $msg = 'Invalid email'): self
    {
        if (!empty($this->data[$f]) && !filter_var($this->data[$f], FILTER_VALIDATE_EMAIL)) $this->errors[$f] = $msg;
        return $this;
    }

    public function fails(): bool { return !empty($this->errors); }
    public function errors(): array { return $this->errors; }
}