<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use Core\Entity\ExchangeRate;
use Core\Repository\ExchangeRateRepositoryInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return ExchangeRate[]
     */
    public function findByDateRange(
        string $currencyFrom,
        string $currencyTo,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ): array {
        $qb = $this->entityManager->createQueryBuilder();

        $result = $qb
            ->select('e')
            ->from(ExchangeRate::class, 'e')
            ->where('e.currencyFrom = :currencyFrom')
            ->andWhere('e.currencyTo = :currencyTo')
            ->andWhere('e.date >= :startDate')
            ->andWhere('e.date <= :endDate')
            ->orderBy('e.date', 'ASC')
            ->setParameter('currencyFrom', $currencyFrom)
            ->setParameter('currencyTo', $currencyTo)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();

        /** @var ExchangeRate[] $result */
        return $result;
    }
}
