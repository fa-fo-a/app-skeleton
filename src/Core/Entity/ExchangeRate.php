<?php

declare(strict_types=1);

namespace Core\Entity;

use DateTimeImmutable;

class ExchangeRate
{
    public function __construct(
        private string $id,
        private string $currencyFrom,
        private string $currencyTo,
        private float $rate,
        private DateTimeImmutable $date,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCurrencyFrom(): string
    {
        return $this->currencyFrom;
    }

    public function getCurrencyTo(): string
    {
        return $this->currencyTo;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
