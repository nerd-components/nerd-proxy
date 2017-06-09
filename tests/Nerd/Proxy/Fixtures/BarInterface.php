<?php

namespace Nerd\Proxy\Fixtures;

interface BarInterface
{
    public function barMethod1();

    public function barMethod2(): void;

    public function barMethod3(string $foo, int $var): void;

    public function barMethod4(): int;
}
