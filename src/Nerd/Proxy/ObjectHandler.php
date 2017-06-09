<?php

namespace Nerd\Proxy;

use \ReflectionMethod;

interface ObjectHandler
{
    public function invoke(string $methodName, array $args, ReflectionMethod $method);
}
