<?php
declare(strict_types=1);

namespace App\Core;

final class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function set(string $id, \Closure $factory): void { $this->bindings[$id] = $factory; }

    public function get(string $id): object
    {
        if (isset($this->instances[$id])) return $this->instances[$id];
        if (!isset($this->bindings[$id])) {
            if (class_exists($id)) return $this->instances[$id] = new $id();
            throw new \RuntimeException("Container: not bound [$id]");
        }
        return $this->instances[$id] = ($this->bindings[$id])($this);
    }
}