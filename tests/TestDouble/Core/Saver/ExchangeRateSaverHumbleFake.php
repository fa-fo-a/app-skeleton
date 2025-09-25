<?php

declare(strict_types=1);

namespace Tests\TestDouble\Core\Saver;

use Core\Saver\ExchangeRateSaverInterface;
use Core\Entity\ExchangeRate;

class ExchangeRateSaverHumbleFake implements ExchangeRateSaverInterface
{
    /**
     * @var ExchangeRate[] $savedEntities
     */
    public array $savedEntities = [];

    public function save(ExchangeRate $exchangeRate): void
    {
        $this->savedEntities[] = $exchangeRate;
    }
}
