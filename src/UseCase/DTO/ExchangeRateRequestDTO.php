<?php

declare(strict_types=1);

namespace UseCase\DTO;

class ExchangeRateRequestDTO
{
    public function __construct(
        public readonly string $currencyFrom,
        public readonly string $currencyTo,
    ) {
    }
}
