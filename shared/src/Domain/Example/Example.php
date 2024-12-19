<?php

namespace Test\Shared\Domain\Example;

use Ecotone\Messaging\Attribute\Asynchronous;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\Distributed;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\Attribute\Identifier;
use Ecotone\Modelling\Attribute\QueryHandler;
use Ecotone\Modelling\WithAggregateVersioning;
use Ramsey\Uuid\UuidInterface;
use Test\Shared\Domain\Example\Command\CreateExample;
use Test\Shared\Domain\Example\Command\DeleteExample;
use Test\Shared\Domain\Example\Command\RenameExample;
use Test\Shared\Domain\Example\Query\GetExampleByName;
use Test\Shared\Service\ExampleService;

#[Asynchronous('exampleChannel')]
#[EventSourcingAggregate]
class Example
{
    use WithAggregateVersioning;

    public const CREATE_EXAMPLE = 'example.create';
    public const RENAME_EXAMPLE = 'example.rename';
    public const DELETE_EXAMPLE = 'example.delete';

    #[Identifier]
    private UuidInterface $uuid;
    private string $name;
    private bool $deleted = false;

    #[Distributed]
    #[CommandHandler(self::CREATE_EXAMPLE, 'Example::create')]
    public static function create(
        CreateExample  $command,
        ExampleService $exampleService,
    ): array
    {
        if ($exampleService->getExampleByName(new GetExampleByName($command->name())) !== null) {
            throw new \Exception('Example with this name already exists');
        }

        return [
            new Event\ExampleWasCreated($command->uuid(), $command->name()),
        ];
    }

    #[Distributed]
    #[CommandHandler(self::RENAME_EXAMPLE, 'Example::rename')]
    public function rename(RenameExample $command): array
    {
        if ($this->deleted) {
            throw new \Exception('Cannot rename deleted example');
        }

        return [
            new Event\ExampleWasRenamed(
                $this->uuid,
                $command->name(),
                $this->name,
            ),
        ];
    }

    #[Distributed]
    #[CommandHandler(self::DELETE_EXAMPLE, 'Example::delete')]
    public function delete(DeleteExample $command): array
    {
        if ($this->deleted) {
            throw new \Exception('Cannot delete deleted example');
        }

        return [
            new Event\ExampleWasDeleted($this->uuid),
        ];
    }

    #[EventSourcingHandler]
    public function applyExampleWasCreated(Event\ExampleWasCreated $event): void
    {
        $this->uuid = $event->uuid();
        $this->name = $event->name();
    }

    #[EventSourcingHandler]
    public function applyExampleWasRenamed(Event\ExampleWasRenamed $event): void
    {
        $this->name = $event->newName();
    }

    #[EventSourcingHandler]
    public function applyExampleWasDeleted(Event\ExampleWasDeleted $event): void
    {
        $this->deleted = true;
    }
}
