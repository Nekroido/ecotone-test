<?php

namespace App\Command;

use Ecotone\Modelling\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Test\Shared\Domain\Example\Command\RenameExample;
use Test\Shared\Domain\Example\Example;

#[AsCommand(
    name: 'example:rename',
    description: 'Add a short description for your command',
)]
class RenameExampleCommand extends Command
{
    public function __construct(private readonly CommandBus $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('uuid', InputArgument::REQUIRED, 'Example UUID')
            ->addArgument('name', InputArgument::REQUIRED, 'Example name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $uuid = $input->getArgument('uuid');
        $name = $input->getArgument('name');

        $this->commandBus->sendWithRouting(Example::RENAME_EXAMPLE, new RenameExample(Uuid::fromString($uuid), $name));

        $io->success('Example renamed');

        return Command::SUCCESS;
    }
}
