<?php

namespace Test\Shared\Domain\Example\Query;

use Ramsey\Uuid\UuidInterface;

class GetExampleById
{
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
