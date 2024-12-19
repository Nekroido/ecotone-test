<?php

namespace Test\Frontend\Infrastructure;

use Ecotone\Amqp\AmqpBackedMessageChannelBuilder;
use Ecotone\Amqp\Distribution\AmqpDistributedBusConfiguration;
use Ecotone\Enqueue\EnqueueMessageChannelBuilder;
use Ecotone\Messaging\Attribute\ServiceContext;

final class MessagingConfiguration
{
    #[ServiceContext]
    public function exampleChannel(): EnqueueMessageChannelBuilder
    {
            return AmqpBackedMessageChannelBuilder::create(
                channelName: "exampleChannel",
            )->withDefaultConversionMediaType("application/json");
    }

    #[ServiceContext]
    public function distributedPublisher(): AmqpDistributedBusConfiguration
    {
        return AmqpDistributedBusConfiguration::createPublisher();
    }

    #[ServiceContext]
    public function distributedConsumer(): AmqpDistributedBusConfiguration
    {
        return AmqpDistributedBusConfiguration::createConsumer();
    }
}
