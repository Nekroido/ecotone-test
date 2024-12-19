<?php

namespace Test\Shared\Domain\Example\Command;

use Ramsey\Uuid\UuidInterface;

class DeleteExample
{
    public const COMMAND_NAME = 'example.delete';

    public function __construct(
        private readonly UuidInterface $uuid
    )
    {
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }
}
