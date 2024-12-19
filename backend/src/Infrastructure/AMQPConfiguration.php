<?php

namespace App\Infrastructure;

use Ecotone\Amqp\Publisher\AmqpMessagePublisherConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;
use Ecotone\Messaging\MessagePublisher;

final class AMQPConfiguration
{
    #[ServiceContext]
    public function registerAmqpConfig(): AmqpMessagePublisherConfiguration
    {
        return
            AmqpMessagePublisherConfiguration::create(
                MessagePublisher::class, // 1
                "delivery", // 2
                "application/json", // 3
            );
    }
}
