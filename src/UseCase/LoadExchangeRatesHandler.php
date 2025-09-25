<?php

declare(strict_types=1);

namespace UseCase;

use DateTimeImmutable;
use Core\Entity\ExchangeRate;
use Core\Generator\UuidGeneratorInterface;
use Core\Saver\ExchangeRateSaverInterface;

class LoadExchangeRatesHandler
{
    /**
     * @var string[]
     */
    private array $currencies = ['USD', 'EUR', 'GBP', 'JPY', 'CHF', 'CAD', 'AUD', 'RUB'];

    public function __construct(
        private readonly ExchangeRateSaverInterface $exchangeRateSaver,
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    public function handle(): int
    {
        $count = 0;
        $today = new DateTimeImmutable();

        for ($day = 0; $day < 30; $day++) {
            $date = $today->modify("-$day days");

            foreach ($this->currencies as $currencyFrom) {
                foreach ($this->currencies as $currencyTo) {
                    if ($currencyFrom === $currencyTo) {
                        continue;
                    }

                    $rate = $this->generateFakeExchangeRate($currencyFrom, $currencyTo);

                    $exchangeRate = new ExchangeRate(
                        $this->uuidGenerator->generate(),
                        $currencyFrom,
                        $currencyTo,
                        $rate,
                        $date
                    );

                    $this->exchangeRateSaver->save($exchangeRate);
                    $count++;
                }
            }
        }

        return $count;
    }

    private function generateFakeExchangeRate(string $from, string $to): float
    {
        $baseRates = [
            'USD' => 1.0,
            'EUR' => 0.85,
            'GBP' => 0.73,
            'JPY' => 110.0,
            'CHF' => 0.92,
            'CAD' => 1.25,
            'AUD' => 1.35,
            'RUB' => 75.0,
        ];

        $baseFromRate = $baseRates[$from];
        $baseToRate = $baseRates[$to];

        $rate = $baseToRate / $baseFromRate;

        $variation = (random_int(95, 105) / 100);

        return round($rate * $variation, 6);
    }
}
