<?php

namespace App\Infrastructure;

use Ecotone\Dbal\DbalBackedMessageChannelBuilder;
use Ecotone\EventSourcing\EventSourcingConfiguration;
use Ecotone\EventSourcing\ProjectionRunningConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;
use Test\Shared\Infrastructure\ReadModelConfiguration;
use Test\Shared\ReadModel\ExamplesProjection;

final class ProjectionConfiguration
{
    #[ServiceContext]
    public function configureEventSourcing(): array
    {
        return [
            EventSourcingConfiguration::createWithDefaults()
                ->withEventStreamTableName('event_streams')
                ->withProjectionsTableName('projections')
                ->withPartitionStreamPersistenceStrategy(),
        ];
    }

    #[ServiceContext]
    public function exampleProjections(): array
    {
        return [
            ProjectionRunningConfiguration::createEventDriven(ExamplesProjection::NAME),
        ];
    }

    #[ServiceContext]
    public function getConfiguration(): array
    {
        return [
            DbalBackedMessageChannelBuilder::create(ReadModelConfiguration::ASYNCHRONOUS_CHANNEL)
        ];
    }
}

