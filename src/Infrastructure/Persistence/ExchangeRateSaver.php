<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use Throwable;
use Core\Entity\ExchangeRate;
use Core\Exception\SaveException;
use Core\Saver\ExchangeRateSaverInterface;
use Doctrine\ORM\EntityManagerInterface;

class ExchangeRateSaver implements ExchangeRateSaverInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function save(ExchangeRate $exchangeRate): void
    {
        try {
            $this->entityManager->persist($exchangeRate);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            SaveException::throwCannotSave($e);
        }
    }
}
