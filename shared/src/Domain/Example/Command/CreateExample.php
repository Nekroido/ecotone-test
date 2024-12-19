<?php

namespace Test\Shared\Domain\Example\Command;

use Ramsey\Uuid\UuidInterface;

class CreateExample
{
    public const COMMAND_NAME = 'example.create';

    public function __construct(
        private readonly UuidInterface $uuid,
        private readonly string        $name
    )
    {
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function name(): string
    {
        return $this->name;
    }
}
