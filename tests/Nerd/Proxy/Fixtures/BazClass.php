<?php

namespace Nerd\Proxy\Fixtures;

class BazClass
{
    public function baz(int $a, int $b): int
    {
        return $a + $b;
    }
}
