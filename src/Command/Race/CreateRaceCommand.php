<?php

declare(strict_types=1);

namespace App\Command\Race;

use App\Application\Command\Race\CreateRaceCommand as CreateRaceCommandApplication;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'f1:race:create',
    description: 'Create a new F1 race'
)]
class CreateRaceCommand extends Command
{

    public function __construct(
        private MessageBusInterface $commandBus,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Race name')
            ->addArgument('circuit', InputArgument::REQUIRED, 'Circuit name')
            ->addArgument('date', InputArgument::REQUIRED, 'Race date (YYYY-MM-DD)')
            ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $command = new CreateRaceCommandApplication(
                $input->getArgument('name'),
                $input->getArgument('circuit'),
                $input->getArgument('date'),
            );

            $this->commandBus->dispatch($command);

            $io->success('Race successfully created');
            return Command::SUCCESS;
        }catch (\Exception $e){
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
