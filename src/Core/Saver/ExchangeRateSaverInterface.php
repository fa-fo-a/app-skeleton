<?php

declare(strict_types=1);

namespace Core\Saver;

use Core\Entity\ExchangeRate;
use Core\Exception\SaveException;

interface ExchangeRateSaverInterface
{
    /**
     * @throws SaveException
     */
    public function save(ExchangeRate $exchangeRate): void;
}
