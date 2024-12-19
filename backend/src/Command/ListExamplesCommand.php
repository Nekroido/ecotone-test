<?php

namespace App\Command;

use Ecotone\Modelling\QueryBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Test\Shared\Domain\Example\Query\ListExamples;

#[AsCommand(
    name: 'example:list',
    description: 'Add a short description for your command',
)]
class ListExamplesCommand extends Command
{
    public function __construct(private readonly QueryBus $queryBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $examples = $this->queryBus->sendWithRouting('examples.list', new ListExamples());

        $io->table(
            ['UUID', 'Name'],
            array_map(
                fn($example) => [$example['uuid'], $example['name']],
                $examples
            )
        );

        return Command::SUCCESS;
    }
}
