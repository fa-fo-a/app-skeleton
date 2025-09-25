<?php

declare(strict_types=1);

namespace Tests\UseCase;

use Core\Entity\ExchangeRate;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\TestDouble\Core\Repository\ExchangeRateRepositoryHumbleFake;
use UseCase\DTO\ExchangeRateRequestDTO;
use UseCase\GetExchangeRatesHandler;

class GetExchangeRatesHandlerTest extends TestCase
{
    private ExchangeRateRepositoryHumbleFake $repository;
    private GetExchangeRatesHandler $handler;

    protected function setUp(): void
    {
        $this->repository = new ExchangeRateRepositoryHumbleFake();
        $this->handler = new GetExchangeRatesHandler($this->repository);
    }

    public function testHandle(): void
    {
        $today = new DateTimeImmutable();
        $yesterday = $today->modify('-1 day');

        $exchangeRate1 = new ExchangeRate('id1', 'USD', 'EUR', 0.85, $today);
        $exchangeRate2 = new ExchangeRate('id2', 'USD', 'EUR', 0.86, $yesterday);
        $exchangeRate3 = new ExchangeRate('id3', 'EUR', 'USD', 1.18, $today);

        $this->repository->exchangeRates = [$exchangeRate1, $exchangeRate2, $exchangeRate3];

        $dto = new ExchangeRateRequestDTO('USD', 'EUR');
        $result = $this->handler->handle($dto);

        $this->assertCount(2, $result);
        $this->assertContains($exchangeRate1, $result);
        $this->assertContains($exchangeRate2, $result);
        $this->assertNotContains($exchangeRate3, $result);
    }

    public function testHandleReturnsEmptyArrayWhenNoRatesFound(): void
    {
        $dto = new ExchangeRateRequestDTO('USD', 'EUR');
        $result = $this->handler->handle($dto);

        $this->assertEmpty($result);
    }
}
