<?php

namespace Test\Shared\Domain\Example\Event;

use Ecotone\Modelling\Attribute\NamedEvent;
use Ramsey\Uuid\UuidInterface;

#[NamedEvent(ExampleWasCreated::EVENT_NAME)]
class ExampleWasCreated
{
    public const EVENT_NAME = 'example.was_created';

    public function __construct(
        private readonly UuidInterface $uuid,
        private readonly string $name
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
