<?php

declare(strict_types=1);

namespace Core\Repository;

use Core\Entity\ExchangeRate;
use DateTimeImmutable;

interface ExchangeRateRepositoryInterface
{
    /**
     * @return ExchangeRate[]
     */
    public function findByDateRange(
        string $currencyFrom,
        string $currencyTo,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ): array;
}
