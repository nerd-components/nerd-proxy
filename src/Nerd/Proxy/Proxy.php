<?php

namespace Nerd\Proxy;

use Closure;
use ReflectionClass;
use ReflectionMethod;

class Proxy
{
    public static function newProxyForObject($object, ObjectHandler $objectProxyHandler)
    {
        $objectClass = get_class($object);
        $proxyHandler = new class($object, $objectProxyHandler) implements Handler
        {
            private $object;
            private $objectProxyHandler;

            public function __construct($object, ObjectHandler $objectProxyHandler) {
                $this->object =$object;
                $this->objectProxyHandler = $objectProxyHandler;
            }

            public function handle(string $name, array $args) {
                $method = new ReflectionMethod($this->object, $name);
                return $this->objectProxyHandler->handle($name, $args, $method);
            }
        };

        return self::newProxyInstance($proxyHandler, [], $objectClass);
    }

    public static function newProxyInstance(Handler $handler, array $interfacesList, $objectClass = null)
    {
        $proxyClass = self::renderClassTemplate(
            $interfacesList,
            $objectClass,
            self::mergeMethods($interfacesList)
        );

        return self::evalAndNewInstance($handler, $proxyClass);
    }

    private static function renderClassTemplate(array $interfaceList, $parentClass, array $methodList): string
    {
        ob_start();
        require(__DIR__ . DIRECTORY_SEPARATOR . 'class.template.php');
        return ob_get_clean();
    }

    private static function evalAndNewInstance(Handler $handler, string $proxyClass)
    {
        $class = self::evalClass($proxyClass);
        return $class($handler);
    }

    private static function evalClass(string $proxyClass): Closure
    {
        return eval($proxyClass);
    }

    private static function mergeMethods(array $interfacesList): array
    {
        return array_merge(
            [],
            ...array_map([self::class, 'getClassMethods'], $interfacesList)
        );
    }

    private static function getClassMethods(string $className): array
    {
        $class = new \ReflectionClass($className);
        return array_map(function (\ReflectionMethod $method) {
            $name = $method->getName();
            $args = self::renderParameters($method);
            $return = self::renderReturnType($method);
            return compact('name', 'args', 'return');
        }, $class->getMethods());
    }

    private static function renderParameters(\ReflectionMethod $method): array
    {
        return array_map([self::class, 'renderParameter'], $method->getParameters());
    }

    public static function getInterfacesList($object): array
    {
        $interfaces = array_map(function (ReflectionClass $interface) {
            return $interface->getName();
        }, (new ReflectionClass($object))->getInterfaces());
        return $interfaces;
    }

    private static function renderParameter(\ReflectionParameter $parameter): string
    {
        return $parameter->hasType()
            ? "{$parameter->getType()} \${$parameter->getName()}"
            : "\${$parameter->getName()}";
    }

    private static function renderReturnType(\ReflectionMethod $method): string
    {
        return $method->hasReturnType()
            ? "{$method->getReturnType()}"
            : "";
    }
}