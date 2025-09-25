<?php

declare(strict_types=1);

namespace UseCase;

use Core\Entity\ExchangeRate;
use DateTimeImmutable;
use UseCase\DTO\ExchangeRateRequestDTO;
use Core\Repository\ExchangeRateRepositoryInterface;

class GetExchangeRatesHandler
{
    public function __construct(
        private readonly ExchangeRateRepositoryInterface $exchangeRateRepository,
    ) {
    }

    /**
     * @return ExchangeRate[]
     */
    public function handle(ExchangeRateRequestDTO $dto): array
    {
        $endDate = new DateTimeImmutable();
        $startDate = $endDate->modify('-1 month');

        return $this->exchangeRateRepository->findByDateRange(
            $dto->currencyFrom,
            $dto->currencyTo,
            $startDate,
            $endDate
        );
    }
}
