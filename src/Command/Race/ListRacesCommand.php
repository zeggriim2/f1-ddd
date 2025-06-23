<?php

declare(strict_types=1);

namespace App\Command\Race;
use App\Application\Query\Race\ListRacesQuery;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[AsCommand(name: 'f1:races:list', description: 'List all F1 races')]
class ListRacesCommand extends Command
{
    public function __construct(
        private MessageBusInterface $queryBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $envelope = $this->queryBus->dispatch(new ListRacesQuery());

        $handleStamp = $envelope->last(HandledStamp::class);
        $races = $handleStamp->getResult();

        if (empty($races)) {
            $io->info('No races found');
            return Command::SUCCESS;
        }

        $rows = array_map(
            fn($race) => [
                $race->name,
                $race->circuit,
                $race->date,
            ],
            $races
        );

        $io->table(['Race', 'Circuit', 'Date'], $rows);

        return Command::SUCCESS;
    }
}
