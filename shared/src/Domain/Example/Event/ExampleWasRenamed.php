<?php

namespace Test\Shared\Domain\Example\Event;

use Ecotone\Modelling\Attribute\NamedEvent;
use Ramsey\Uuid\UuidInterface;

#[NamedEvent(ExampleWasRenamed::EVENT_NAME)]
class ExampleWasRenamed
{
    public const EVENT_NAME = 'example.was_renamed';

    public function __construct(
        private readonly UuidInterface $uuid,
        private readonly string        $newName,
        private readonly string        $oldName
    )
    {
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function newName(): string
    {
        return $this->newName;
    }

    public function oldName(): string
    {
        return $this->oldName;
    }
}
