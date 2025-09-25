<?php

declare(strict_types=1);

namespace Tests\Core\Entity;

use Core\Entity\ExchangeRate;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ExchangeRateTest extends TestCase
{
    public function testCreationAndGetters(): void
    {
        $entityId = 'test-id-123';
        $currencyFrom = 'USD';
        $currencyTo = 'EUR';
        $rate = 0.85;
        $date = new DateTimeImmutable('2025-09-25');

        $exchangeRate = new ExchangeRate($entityId, $currencyFrom, $currencyTo, $rate, $date);

        $this->assertSame($entityId, $exchangeRate->getId());
        $this->assertSame($currencyFrom, $exchangeRate->getCurrencyFrom());
        $this->assertSame($currencyTo, $exchangeRate->getCurrencyTo());
        $this->assertSame($rate, $exchangeRate->getRate());
        $this->assertSame($date, $exchangeRate->getDate());
    }
}
