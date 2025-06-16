<?php

namespace App\Command;

use App\DTO\RecordRaceInputDTO;
use App\Services\RaceResultService;
use App\ValueObject\LapTime;
use App\ValueObject\Position;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:record-race-result',
    description: 'Record a race result using Value Objects'
)]
class RecordRaceResultCommand extends Command
{
    public function __construct(
        private readonly RaceResultService $raceResultService,
        private readonly ValidatorInterface $validator,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('driverId', InputArgument::REQUIRED, 'Driver id')
            ->addArgument('raceId', InputArgument::REQUIRED, 'Race id')
            ->addArgument('position', InputArgument::REQUIRED, 'Position')
            ->addArgument('bestLapTime', InputArgument::REQUIRED, 'Best lap')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $driverId = (int) $input->getArgument('driverId');
        $raceId = (int) $input->getArgument('raceId');
        $position = new Position((int) $input->getArgument('position'));
        $bestLapTime = LapTime::fromString((string) $input->getArgument('bestLapTime'));

        $dto = new RecordRaceInputDTO($driverId, $raceId, $position, $bestLapTime);

        $result = $this->raceResultService->recordRaceResult($dto);

        $this->validator->validate($dto);
        // Affichage du résultat
        $io->success('Race result recorded successfully!');
        $io->table(
            ['Fields', 'Value'],
            [
                ['ID', $result->id],
                ['Driver Name', $result->driverName],
                ['Team Name', $result->teamName],
                ['Position', $result->position],
                ['Position', $result->bestLapTime],
                ['Points', $result->points],
                ['Is Podium', $result->isPodium],
                ['Is Win', $result->isWin],
                ['Recorded At', $result->recordedAt],
            ]
        );

        return Command::SUCCESS;
    }
}
