<?php

namespace Nerd\Proxy;

use \Nerd\Proxy\Fixtures\BarInterface;
use Nerd\Proxy\Fixtures\BazClass;
use \Nerd\Proxy\Fixtures\FooInterface;
use \PHPUnit\Framework\TestCase;
use \ReflectionMethod;

class ProxyTest extends TestCase
{
    public function testNewInstance()
    {
        $interfaces = [FooInterface::class, BarInterface::class];
        $instance = Proxy::newProxyInstance(new class implements Handler {
            public function invoke(ReflectionMethod $method, array $args, $proxyInstance)
            {
                if ($method->getName() == 'barMethod4') {
                    return 10;
                }
                return null;
            }
        }, $interfaces, null);

        $this->assertInstanceOf(FooInterface::class, $instance);
        $this->assertInstanceOf(BarInterface::class, $instance);

        $this->assertEquals(10, $instance->barMethod4());
    }

    public function testObjectProxy()
    {
        $object = new BazClass();
        $this->assertEquals(15, $object->baz(5, 10));

        $objectProxy = Proxy::newProxyForObject($object, new class implements Handler {
            public function invoke(ReflectionMethod $method, array $args, $proxyInstance)
            {
                return $method->invokeArgs($proxyInstance, $args);
            }
        });
        $this->assertInstanceOf(BazClass::class, $objectProxy);
        $this->assertEquals(15, $objectProxy->baz(5, 10));
    }
}
