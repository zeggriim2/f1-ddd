<?php

namespace App\Command;

use App\Services\RaceResultService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:classement-drivers',
    description: 'Add a short description for your command',
)]
final class ClassementDriversCommand extends Command
{
    public function __construct(
        private readonly RaceResultService $raceResultService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $standings = $this->raceResultService->getDriverStandings();

        $io->table(
            array_keys($standings[array_key_first($standings)]),
            $standings
        );

        return Command::SUCCESS;
    }
}
