<?php

declare(strict_types=1);

namespace Tests\TestDouble\Core\Repository;

use Core\Entity\ExchangeRate;
use Core\Repository\ExchangeRateRepositoryInterface;
use DateTimeImmutable;

class ExchangeRateRepositoryHumbleFake implements ExchangeRateRepositoryInterface
{
    /**
     * @var ExchangeRate[]
     */
    public array $exchangeRates = [];

    public function findByDateRange(
        string $currencyFrom,
        string $currencyTo,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ): array {
        return array_filter(
            $this->exchangeRates,
            static function (ExchangeRate $rate) use ($currencyFrom, $currencyTo, $startDate, $endDate): bool {
                return $rate->getCurrencyFrom() === $currencyFrom
                && $rate->getCurrencyTo() === $currencyTo
                && $rate->getDate() >= $startDate
                && $rate->getDate() <= $endDate;
            }
        );
    }
}
