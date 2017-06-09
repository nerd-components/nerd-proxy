<?php

namespace Nerd\Proxy;

interface Handler
{
    public function invoke(string $methodName, array $args);
}