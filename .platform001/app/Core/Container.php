<?php
declare(strict_types=1);

namespace App\Core;

final class Container
{
    /** @var array<string, \Closure> */
    private array $bindings = [];

    /** @var array<string, object> */
    private array $instances = [];

    public function set(string $id, \Closure $factory): void
    {
        $this->bindings[$id] = $factory;
    }

    public function get(string $id): object
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->bindings[$id])) {
            return $this->instances[$id] = ($this->bindings[$id])($this);
        }

        if (class_exists($id)) {
            return $this->instances[$id] = $this->build($id);
        }

        throw new \RuntimeException("Container: not bound [$id]");
    }

    /**
     * Автовайринг: рекурсивно резолвит зависимости конструктора.
     */
    private function build(string $class): object
    {
        $ref = new \ReflectionClass($class);

        if (!$ref->isInstantiable()) {
            throw new \RuntimeException("Container: cannot instantiate [$class]");
        }

        $ctor = $ref->getConstructor();
        if ($ctor === null || $ctor->getNumberOfParameters() === 0) {
            return $ref->newInstance();
        }

        $args = [];
        foreach ($ctor->getParameters() as $param) {
            $type = $param->getType();

            if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
                if ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                    continue;
                }
                throw new \RuntimeException(
                    "Container: cannot resolve parameter \${$param->getName()} of [$class]"
                );
            }

            $depClass = $type->getName();
            $args[] = $this->get($depClass);
        }

        return $ref->newInstanceArgs($args);
    }
}