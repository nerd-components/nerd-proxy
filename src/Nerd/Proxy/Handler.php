<?php

namespace Nerd\Proxy;

interface Handler
{
    public function handle(string $name, array $args);
}