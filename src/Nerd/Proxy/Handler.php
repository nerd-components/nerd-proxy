<?php

namespace Nerd\Proxy;

use ReflectionMethod;

interface Handler
{
    public function invoke(ReflectionMethod $method, array $args, $proxyInstance);
}
