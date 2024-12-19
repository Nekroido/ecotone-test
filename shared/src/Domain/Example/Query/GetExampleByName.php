<?php

namespace Test\Shared\Domain\Example\Query;

class GetExampleByName
{
    public function __construct(
        private readonly string $name,
    ) {}

    public function name(): string
    {
        return $this->name;
    }
}
