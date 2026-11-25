<?php
declare(strict_types=1);

namespace App\Core;

final class Logger
{
    public function __construct(private string $file) {}

    public function info(string $msg, array $ctx = []): void  { $this->write('INFO',  $msg, $ctx); }
    public function warn(string $msg, array $ctx = []): void  { $this->write('WARN',  $msg, $ctx); }
    public function error(string $msg, array $ctx = []): void { $this->write('ERROR', $msg, $ctx); }

    private function write(string $level, string $msg, array $ctx): void
    {
        $line = sprintf(
            "[%s] %s %s %s\n",
            date('Y-m-d H:i:s'),
            $level,
            $msg,
            $ctx ? json_encode($ctx, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : ''
        );
        @file_put_contents($this->file, $line, FILE_APPEND | LOCK_EX);
    }
}