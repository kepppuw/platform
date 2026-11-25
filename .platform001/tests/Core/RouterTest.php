<?php
declare(strict_types=1);

namespace Tests\Core;

use App\Core\{Container, Router};
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testPatternCompiles(): void
    {
        $router = new Router(new Container(), []);
        $ref = new \ReflectionMethod($router, 'compile');
        $ref->setAccessible(true);
        [$regex, $params] = $ref->invoke($router, '/user/{id}-{slug}');
        $this->assertSame(1, preg_match($regex, '/user/42-ivan'));
        $this->assertSame(['id', 'slug'], $params);
    }

    public function testSimplePath(): void
    {
        $router = new Router(new Container(), []);
        $ref = new \ReflectionMethod($router, 'compile');
        $ref->setAccessible(true);
        [$regex] = $ref->invoke($router, '/shop');
        $this->assertSame(1, preg_match($regex, '/shop'));
        $this->assertSame(0, preg_match($regex, '/shop/x'));
    }
}