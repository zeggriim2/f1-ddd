<?php

declare(strict_types=1);

namespace App\Command\RaceResult;

use App\Application\Command\RaceResult\RegisterRaceResultCommand as RegisterRaceResultCommandApplication;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'f1:result:register', description: 'Register a race result')]
class RegisterRaceResultCommand extends Command
{
    public function __construct(private MessageBusInterface $commandBus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('raceNane', InputArgument::REQUIRED, 'Race Nane')
            ->addArgument('driverNumber', InputArgument::REQUIRED, 'Driver number')
            ->addArgument('position',  InputArgument::REQUIRED, 'Final Position')
            ->addArgument('lapTime', InputArgument::REQUIRED, 'Best lap time (M:SS. mmm')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);

        try {
            $command = new RegisterRaceResultCommandApplication(
                $input->getArgument('raceNane'),
                (int) $input->getArgument('driverNumber'),
                (int) $input->getArgument('position'),
                $input->getArgument('lapTime'),
            );

            $this->commandBus->dispatch($command);
            $io->success('Race result registered successfully');

            return Command::SUCCESS;
        } catch (\Exception $e){
            $io->error('Error :' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
