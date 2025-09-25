<?php

declare(strict_types=1);

namespace Infrastructure\Console\Command;

use UseCase\LoadExchangeRatesHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-exchange-rates',
    description: 'Load fake exchange rates into the database',
)]
class LoadExchangeRatesCommand extends Command
{
    public function __construct(
        private readonly LoadExchangeRatesHandler $loadExchangeRatesHandler,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Loading Exchange Rates');

        $count = $this->loadExchangeRatesHandler->handle();

        $io->success("Successfully loaded $count exchange rates for the last 30 days.");

        return Command::SUCCESS;
    }
}
