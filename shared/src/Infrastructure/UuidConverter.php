<?php

namespace Test\Shared\Infrastructure;

use Ecotone\Messaging\Attribute\Converter;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidConverter
{
    #[Converter]
    public function to(string $uuid): UuidInterface
    {
        return Uuid::fromString($uuid);
    }

    #[Converter]
    public function from(UuidInterface $uuid): string
    {
        return $uuid->toString();
    }
}
