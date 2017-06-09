<?php

namespace Nerd\Proxy\Fixtures;

class BazClass
{
    public function baz(int $a, int $b): int
    {
        return $this->bass($a + $b);
    }

    protected function bass(int $val): int
    {
        return $val * 2;
    }
}
