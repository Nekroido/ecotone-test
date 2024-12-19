<?php

namespace Test\Shared\ReadModel;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\UniqueConstraint;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Ecotone\EventSourcing\Attribute\Projection;
use Ecotone\EventSourcing\Attribute\ProjectionDelete;
use Ecotone\EventSourcing\Attribute\ProjectionInitialization;
use Ecotone\EventSourcing\Attribute\ProjectionReset;
use Ecotone\Messaging\Attribute\Asynchronous;
use Ecotone\Messaging\Attribute\Parameter\Header;
use Ecotone\Messaging\MessageHeaders;
use Ecotone\Modelling\Attribute\Distributed;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use Test\Shared\Domain\Example\Event\ExampleWasCreated;
use Test\Shared\Domain\Example\Event\ExampleWasDeleted;
use Test\Shared\Domain\Example\Event\ExampleWasRenamed;
use Test\Shared\Domain\Example\Example;
use Test\Shared\Domain\Example\Query\GetExampleById;
use Test\Shared\Domain\Example\Query\GetExampleByName;
use Test\Shared\Domain\Example\Query\ListExamples;
use Test\Shared\Infrastructure\ReadModelConfiguration;
use Test\Shared\Service\ExampleService;

#[Asynchronous(ReadModelConfiguration::ASYNCHRONOUS_CHANNEL)]
#[Projection(ExamplesProjection::NAME, Example::class)]
class ExamplesProjection
{
    public const NAME = 'examples';
    public const TABLE_NAME = self::NAME;

    public function __construct(private readonly Connection $connection)
    {
    }

    #[ProjectionInitialization]
    public function initialize(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist([self::TABLE_NAME])) {
            return;
        }

        $schemaManager->createTable(
            new Table(
                self::TABLE_NAME,
                [
                    new Column("uuid", Type::getType(Types::GUID)),
                    new Column("name", Type::getType(Types::ASCII_STRING)),
                    new Column("created_at", Type::getType(Types::DATETIMETZ_IMMUTABLE)),
                    new Column(
                        "updated_at",
                        Type::getType(Types::DATETIMETZ_IMMUTABLE),
                        ["notnull" => false],
                    ),
                ],
                uniqueConstraints: [
                    new UniqueConstraint("uuid_idx", ["uuid"]),
                    new UniqueConstraint("name_idx", ["name"]),
                ],
            ),
        );
    }

    #[ProjectionReset]
    public function reset(): void
    {
        $this->connection->executeStatement("DELETE FROM " . self::TABLE_NAME);
    }

    #[ProjectionDelete]
    public function remove(): void
    {
        $schemaManager = $this->connection->createSchemaManager();
        if ($schemaManager->tablesExist(self::TABLE_NAME)) {
            return;
        }

        $schemaManager->dropTable(self::TABLE_NAME);
    }

    #[EventHandler(listenTo: ExampleWasCreated::EVENT_NAME, endpointId: 'ExamplesProjection::applyExampleCreated')]
    public function applyExampleCreated(ExampleWasCreated                    $event,
                                        #[Header(MessageHeaders::TIMESTAMP)] $occurredOn,
    ): void
    {
        $this->connection->insert(
            self::TABLE_NAME,
            [
                'uuid' => $event->uuid()->toString(),
                'name' => $event->name(),
                'created_at' => DateTimeImmutable::createFromFormat('U', $occurredOn)->format('Y-m-d H:i:s'),
            ],
        );
    }

    #[EventHandler(listenTo: ExampleWasRenamed::EVENT_NAME, endpointId: 'ExamplesProjection::applyExampleRenamed')]
    public function applyExampleRenamed(ExampleWasRenamed                    $event,
                                        #[Header(MessageHeaders::TIMESTAMP)] $occurredOn,
    ): void
    {
        $this->connection->update(
            self::TABLE_NAME,
            [
                'name' => $event->newName(),
                'updated_at' => DateTimeImmutable::createFromFormat('U', $occurredOn)->format('Y-m-d H:i:s'),
            ],
            [
                'uuid' => $event->uuid()->toString(),
            ],
        );
    }

    #[EventHandler(listenTo: ExampleWasDeleted::EVENT_NAME, endpointId: 'ExamplesProjection::applyExampleDeleted')]
    public function applyExampleDeleted(ExampleWasDeleted $event): void
    {
        $this->connection->delete(
            self::TABLE_NAME,
            [
                'uuid' => $event->uuid()->toString(),
            ],
        );
    }

    #[Distributed]
    #[QueryHandler(ExampleService::LIST_EXAMPLES, 'ExamplesProjection::listAll')]
    public function listAll(ListExamples $query): array
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_NAME)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    #[Distributed]
    #[QueryHandler(ExampleService::GET_EXAMPLE_BY_ID, 'ExamplesProjection::getOne')]
    public function getOne(GetExampleById $query): array
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where('uuid = :uuid')
            ->setParameter('uuid', $query->uuid()->toString())
            ->executeQuery()
            ->fetchAssociative();
    }

    #[Distributed]
    #[QueryHandler(ExampleService::GET_EXAMPLE_BY_NAME, 'ExamplesProjection::getByName')]
    public function getByName(GetExampleByName $query): array
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where('name = :name')
            ->setParameter('name', $query->name())
            ->executeQuery()
            ->fetchAssociative();
    }
}
