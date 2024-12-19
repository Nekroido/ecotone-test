<?php

namespace Test\Shared\Domain\Example\Event;

use Ecotone\Modelling\Attribute\NamedEvent;
use Ramsey\Uuid\UuidInterface;

#[NamedEvent(ExampleWasDeleted::EVENT_NAME)]
class ExampleWasDeleted
{
    public const EVENT_NAME = 'example.was_deleted';

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
