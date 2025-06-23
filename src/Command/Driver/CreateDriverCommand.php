<?php

declare(strict_types=1);

namespace App\Command\Driver;

use App\Application\Command\Driver\CreateDriverCommand as CreateDriverCommandApplication;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'f1:driver:create', description: 'Create a new F1 driver')]
class CreateDriverCommand extends Command
{
    public function __construct(private MessageBusInterface $commandBus) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('firstName', InputArgument::REQUIRED, 'Driver first name')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Driver last name')
            ->addArgument('nationality', InputArgument::REQUIRED, 'Driver nationality')
            ->addArgument('number', InputArgument::REQUIRED, 'Driver number')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $command = new CreateDriverCommandApplication(
                $input->getArgument('firstName'),
                $input->getArgument('lastName'),
                $input->getArgument('nationality'),
                (int) $input->getArgument('number'),
            );

            $this->commandBus->dispatch($command);

            $io->success('Driver created successfully');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
