# Nerd Proxy
A lightweight object proxy for PHP 7.

## Usage
Create object implementing given interfaces:
```php
<?php

use \Nerd\Proxy\Proxy;
use \Nerd\Proxy\Handler;

interface FooInterface {
    public function foo(): string;
}

interface BarInterface {
    public function bar(): string;
}

$interfacesList = [FooInterface::class, BarInterface::class];

$handler = new class implements Handler {
    public function invoke(string $methodName, array $args) {
        switch ($methodName) {
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

```