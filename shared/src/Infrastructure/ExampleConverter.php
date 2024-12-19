<?php

namespace Test\Shared\Infrastructure;

use Ecotone\Messaging\Attribute\Converter;
use Ramsey\Uuid\Uuid;
use Test\Shared\Dto\ExampleDto;

class ExampleConverter
{
    #[Converter]
    public function fromArray(array $example): ExampleDto
    {
        return new ExampleDto(
            Uuid::fromString($example['id']),
            $example['name'],
        );
    }

    #[Converter]
    public function toArray(ExampleDto $example): array
    {
        return [
            'id' => $example->uuid()->toString(),
            'name' => $example->name(),
        ];
    }
}
