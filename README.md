# Nerd Proxy
A lightweight object proxy for PHP 7.

## Usage
Create object implementing given interfaces:
```php
<?php

use \Nerd\Proxy\Proxy;
use \Nerd\Proxy\Handler;
use \ReflectionMethod;

interface FooInterface {
    public function foo(): string;
}

interface BarInterface {
    public function bar(): string;
}

$interfacesList = [FooInterface::class, BarInterface::class];

$handler = new class implements Handler {
    public function invoke(ReflectionMethod $method, array $args, $proxyInstance) {
        switch ($method->getName()) {
            case 'foo':
                return 'foo called';
            case 'bar':
                return 'bar called';
        }
    }
};

$proxy = Proxy::newProxyInstance($handler, $interfacesList);

$proxy instanceof FooInterface; // true
$proxy instanceof BarInterface; // true

$proxy->foo(); // 'foo called'
$proxy->bar(); // 'bar called'
```

Create proxy for given object:
```php
<?php

use \Nerd\Proxy\Proxy;
use \Nerd\Proxy\Handler;

$object = new class {
    public function foo(): int {
        echo "Foo! ";
        return 10;
    }
};

$objectProxy = Proxy::newProxyForObject($object, new class implements Handler {
    public function invoke(ReflectionMethod $method, array $args, $proxyInstance) {
        echo "Before call. ";
        $result = $method->invokeArgs($proxyInstance, $args);
        echo "After call.";
        return $result;
    }
});

$objectProxy->foo(); // will print: 'Before call. Foo! After call.' and then return 10 
```
