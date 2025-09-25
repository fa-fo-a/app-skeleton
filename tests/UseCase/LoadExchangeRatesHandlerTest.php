<?php

declare(strict_types=1);

namespace Tests\UseCase;

use PHPUnit\Framework\TestCase;
use Tests\TestDouble\Core\Generator\UuidGeneratorHumbleFake;
use Tests\TestDouble\Core\Saver\ExchangeRateSaverHumbleFake;
use UseCase\LoadExchangeRatesHandler;

class LoadExchangeRatesHandlerTest extends TestCase
{
    private UuidGeneratorHumbleFake $uuidGenerator;
    private ExchangeRateSaverHumbleFake $exchangeRateSaver;
    private LoadExchangeRatesHandler $handler;

    protected function setUp(): void
    {
        $this->uuidGenerator = new UuidGeneratorHumbleFake();
        $this->exchangeRateSaver = new ExchangeRateSaverHumbleFake();

        $this->handler = new LoadExchangeRatesHandler(
            $this->exchangeRateSaver,
            $this->uuidGenerator
        );
    }

    public function testHandle(): void
    {
        $this->uuidGenerator->uuid = 'test-uuid';

        $count = $this->handler->handle();

        $expectedCount = 8 * 7 * 30; // 8 currencies, each to 7 others, 30 days
        $this->assertSame($expectedCount, $count);

        $this->assertCount($expectedCount, $this->exchangeRateSaver->savedEntities);

        $firstExchangeRate = $this->exchangeRateSaver->savedEntities[0];
        $this->assertSame('test-uuid', $firstExchangeRate->getId());
        $currencies = ['USD', 'EUR', 'GBP', 'JPY', 'CHF', 'CAD', 'AUD', 'RUB'];
        $this->assertContains($firstExchangeRate->getCurrencyFrom(), $currencies);
        $this->assertContains($firstExchangeRate->getCurrencyTo(), $currencies);
        $this->assertNotSame($firstExchangeRate->getCurrencyFrom(), $firstExchangeRate->getCurrencyTo());
        $this->assertGreaterThan(0, $firstExchangeRate->getRate());
    }
}
