<?php

namespace Test\Shared\Dto;

use Ramsey\Uuid\UuidInterface;

class ExampleDto
{
    public function __construct(
        private readonly UuidInterface $id,
        private readonly string        $name,
    )
    {
    }

    public function uuid(): UuidInterface
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
