<?php

declare(strict_types=1);

namespace App\Command\RaceResult;

use App\Application\Query\GetResultsBySpecificationQuery;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'f1:result:filter',
    description: 'Filter race result by specification',
)]
class FilterRaceResultsCommand extends Command
{
    public function __construct(
        private MessageBusInterface $queryBus
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('raceName', InputArgument::REQUIRED, 'Race name')
            ->addArgument('type', InputArgument::REQUIRED, 'Filter type: podium, points, winner')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $type = $input->getArgument('type');

            $results = $this->queryBus->dispatch(
                new GetResultsBySpecificationQuery(
                    $input->getArgument('raceName'),
                    $type
                )
            );

            if (empty($results)) {
                $io->info('No results match the specification');
                return Command::SUCCESS;
            }

            $rows = array_map(
                fn($result) => [
                    $result['position'],
                    $result['driver'],
                    $result['points'],
                ],
                $results
            );

            $io->title(sprintf("Results filtered by %s specification", $type));
            $io->table(['Position', 'Driver', 'Points'], $rows);

            return Command::SUCCESS;
        }catch (\Exception $e){
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
