<?php

namespace Nerd\Proxy\Fixtures;

interface FooInterface
{
    public function fooMethod1();

    public function fooMethod2(): void;

    public function fooMethod3(string $foo, int $var): void;

    public function fooMethod4(): int;
}
