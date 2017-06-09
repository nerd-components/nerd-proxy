<?php

namespace Nerd\Proxy;

use \Closure;

class Proxy
{
    public static function newProxyForObject($object, Handler $handler)
    {
        $objectClass = get_class($object);
        return self::newProxyInstance($handler, [], $objectClass);
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
            $visibility = self::renderMethodVisibility($method);
            $name = $method->getName();
            $args = self::renderParameters($method);
            $return = self::renderReturnType($method);
            return compact('name', 'args', 'return', 'visibility');
        }, $class->getMethods());
    }

    private static function renderParameters(\ReflectionMethod $method): array
    {
        return array_map([self::class, 'renderParameter'], $method->getParameters());
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

    private static function renderMethodVisibility(\ReflectionMethod $method): string
    {
        return $method->isPrivate() ? 'private' : ($method->isProtected() ? 'protected' : 'public');
    }
}
