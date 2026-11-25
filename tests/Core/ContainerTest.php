<?php
declare(strict_types=1);

namespace Tests\Core;

use App\Core\Container;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function testSingletonBinding(): void
    {
        $c = new Container();
        $c->set('x', fn() => new \stdClass());
        $this->assertSame($c->get('x'), $c->get('x'));
    }

    public function testUnknownThrows(): void
    {
        $this->expectException(\RuntimeException::class);
        (new Container())->get('nope');
    }
}