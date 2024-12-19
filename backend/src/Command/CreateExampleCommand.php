<?php

namespace App\Command;

use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Test\Shared\Domain\Example\Command\CreateExample;
use Test\Shared\Domain\Example\Example;
use Test\Shared\Domain\Example\Query\GetExampleById;

#[AsCommand(
    name: 'example:create',
    description: 'Creates an example object',
)]
class CreateExampleCommand extends Command
{
    public function __construct(private readonly CommandBus $commandBus, private readonly QueryBus $queryBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Example name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');

        $uuid = Uuid::uuid4();
        $this->commandBus->sendWithRouting(Example::CREATE_EXAMPLE, new CreateExample($uuid, $name));

        $example = $this->queryBus->sendWithRouting('examples.get', new GetExampleById($uuid));

        $io->note(sprintf('Example created with UUID: %s', $example->uuid()->toString()));

        return Command::SUCCESS;
    }
}
