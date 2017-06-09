<?php

namespace Nerd\Proxy;

use Nerd\Proxy\Fixtures\BarInterface;
use Nerd\Proxy\Fixtures\FooInterface;
use PHPUnit\Framework\TestCase;

class ProxyTest extends TestCase
{
    public function testNewInstance()
    {
        $interfaces = [FooInterface::class, BarInterface::class];
        $instance = Proxy::newProxyInstance(new class implements Handler {
            public function handle(string $name, array $args)
            {
                if ($name == 'barMethod4') {
                    return 10;
                }
                return null;
            }
        }, $interfaces, null);

        $this->assertInstanceOf(FooInterface::class, $instance);
        $this->assertInstanceOf(BarInterface::class, $instance);

        $this->assertEquals(10, $instance->barMethod4());
    }
}
