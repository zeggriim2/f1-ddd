<?php

declare(strict_types=1);

namespace App\Command\Driver;

use App\Application\Query\Driver\ListDriversQuery;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[AsCommand(name: 'f1:driver:list', description: 'List all F1 drivers')]
class ListDriversCommand extends Command
{
    public function __construct(private MessageBusInterface $queryBus) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var Envelope $envelope */
        $envelope = $this->queryBus->dispatch(new ListDriversQuery());

        $handledStamp = $envelope->last(HandledStamp::class);
        $drivers = $handledStamp->getResult();

        if (empty($drivers)) {
            $io->warning('No drivers found');
            return Command::SUCCESS;
        }

        // ✅ PRÉSENTATION : Formatage pour la console
        $rows = array_map(
            fn($driver) => [
                $driver->number,
                $driver->fullName,
                $driver->nationality
            ],
            $drivers
        );

        $io->table(['Number', 'Name', 'Nationality'], $rows);

        return Command::SUCCESS;
    }
}
