<?php

namespace Test\Frontend\Infrastructure;

use Ecotone\JMSConverter\JMSConverterConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;

final class EcontoneConfiguration
{
//    #[ServiceContext]
    public function getJmsConfiguration(): JMSConverterConfiguration
    {
        return JMSConverterConfiguration::createWithDefaults()
                                        ->withDefaultNullSerialization(isEnabled: true)
                                        ->withNamingStrategy("camelCasePropertyNamingStrategy");
    }
}
