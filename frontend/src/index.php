<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Ecotone\Amqp\AmqpAdmin;
use Ecotone\Amqp\AmqpQueue;
use Ecotone\Lite\EcotoneLiteApplication;
use Ecotone\Messaging\Config\ServiceConfiguration;
use Ecotone\Messaging\Endpoint\ExecutionPollingMetadata;
use Enqueue\AmqpExt\AmqpConnectionFactory;

$exampleQueue = AmqpQueue::createWith("exampleChannel");

$application = EcotoneLiteApplication::bootstrap(
    [
        AmqpConnectionFactory::class => new AmqpConnectionFactory(
            "amqp+lib://guest:guest@rabbitmq:5672//",
        ),
        'amqp_admin' => AmqpAdmin::createWith(
            [],
            [$exampleQueue],
            [],
        ),
    ],
    serviceConfiguration: ServiceConfiguration::createWithAsynchronicityOnly()
        ->withServiceName('frontend_service')
        ->withNamespaces(
            [
                'Test\\Frontend\\',
                'Test\\Shared\\Domain\\',
                'Test\\Shared\\Dto\\',
                'Test\\Shared\\Infrastructure\\',
                'Test\\Shared\\Service\\',
            ],
        )
        ->doNotLoadCatalog(),
);

var_dump($application->list());exit;

$application->run(
    'exampleChannel',
    ExecutionPollingMetadata::createWithTestingSetup(
        100,
        1000,
    ),
);

$examples = $application->getQueryBus()->sendWithRouting('examples.list');

foreach ($examples as $example) {
    echo "Example with UUID: " . $example['uuid'] . " and name: " . $example['name'] . PHP_EOL;
}

echo 'Done';
