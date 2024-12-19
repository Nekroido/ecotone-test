<?php

namespace Test\Shared\Service;

use Ecotone\Messaging\Attribute\BusinessMethod;
use Test\Shared\Domain\Example\Query\GetExampleById;
use Test\Shared\Domain\Example\Query\GetExampleByName;
use Test\Shared\Domain\Example\Query\ListExamples;
use Test\Shared\Dto\ExampleDto;

interface ExampleService
{
    public const LIST_EXAMPLES = 'examples.list';
    public const GET_EXAMPLE_BY_ID = 'examples.getById';
    public const GET_EXAMPLE_BY_NAME = 'examples.getByName';

    #[BusinessMethod(self::LIST_EXAMPLES)]
    public function listExamples(ListExamples $query): array;

    #[BusinessMethod(self::GET_EXAMPLE_BY_ID)]
    public function getExampleById(GetExampleById $query): ?ExampleDto;

    #[BusinessMethod(self::GET_EXAMPLE_BY_NAME)]
    public function getExampleByName(GetExampleByName $query): ?ExampleDto;
}
