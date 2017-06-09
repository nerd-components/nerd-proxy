<?php

namespace Nerd\Proxy;

use \ReflectionMethod;

interface ObjectHandler
{
    public function handle(string $name, array $args, ReflectionMethod $method);
}
