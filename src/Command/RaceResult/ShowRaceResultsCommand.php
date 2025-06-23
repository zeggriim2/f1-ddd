<?php

declare(strict_types=1);

namespace App\Command\RaceResult;

use App\Application\DTO\RaceResultDTO;
use App\Application\Query\Race\GetRaceResultQuery;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[AsCommand(name: 'f1:results:show', description: 'Show race results')]
class ShowRaceResultsCommand extends Command
{
    public function __construct(private MessageBusInterface $queryBus) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('raceName', InputArgument::REQUIRED, 'Race name');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $envelope = $this->queryBus->dispatch(
            new GetRaceResultQuery($input->getArgument('raceName'))
        );

        $handledStamp = $envelope->last(HandledStamp::class);
        /** @var RaceResultDTO[] $raceResults */
        $raceResults = $handledStamp->getResult();

        if (empty($raceResults)) {
            $io->info('No results found for this race.');
            return Command::SUCCESS;
        }

        // ✅ FORMATAGE AVANCÉ : Avec émojis et couleurs
        $rows = array_map(
            fn($raceResult) => [
                $raceResult->position,
                $raceResult->driverFullName,
                $raceResult->bestLapTime,
                $raceResult->points,
                $raceResult->isPodiumFinish ? '🏆' : ''  // ✅ Logique métier dans le DTO
            ],
            $raceResults
        );

        $io->table(['Pos', 'Driver', 'Best Lap', 'Points', 'Podium'], $rows);

        return Command::SUCCESS;
    }
}
