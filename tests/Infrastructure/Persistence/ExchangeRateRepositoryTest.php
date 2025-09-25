<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence;

use Core\Entity\ExchangeRate;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Infrastructure\Persistence\ExchangeRateRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExchangeRateRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private ExchangeRateRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->repository = new ExchangeRateRepository($this->entityManager);

        $this->cleanupExchangeRates();
    }

    protected function tearDown(): void
    {
        $this->cleanupExchangeRates();
        parent::tearDown();
    }

    public function testFindByDateRange(): void
    {
        $today = new DateTimeImmutable('2025-09-25');
        $yesterday = new DateTimeImmutable('2025-09-24');
        $twoDaysAgo = new DateTimeImmutable('2025-09-23');

        $exchangeRate1 = new ExchangeRate('test-1', 'USD', 'EUR', 0.85, $today);
        $exchangeRate2 = new ExchangeRate('test-2', 'USD', 'EUR', 0.86, $yesterday);
        $exchangeRate3 = new ExchangeRate('test-3', 'USD', 'GBP', 0.73, $today);
        $exchangeRate4 = new ExchangeRate('test-4', 'USD', 'EUR', 0.87, $twoDaysAgo);

        $this->entityManager->persist($exchangeRate1);
        $this->entityManager->persist($exchangeRate2);
        $this->entityManager->persist($exchangeRate3);
        $this->entityManager->persist($exchangeRate4);
        $this->entityManager->flush();

        $startDate = new DateTimeImmutable('2025-09-24');
        $endDate = new DateTimeImmutable('2025-09-25');

        $result = $this->repository->findByDateRange('USD', 'EUR', $startDate, $endDate);

        $this->assertCount(2, $result);

        $ids = array_map(fn($rate) => $rate->getId(), $result);
        $this->assertContains('test-1', $ids);
        $this->assertContains('test-2', $ids);
        $this->assertNotContains('test-3', $ids);
        $this->assertNotContains('test-4', $ids);

        $this->assertSame('2025-09-24', $result[0]->getDate()->format('Y-m-d'));
        $this->assertSame('2025-09-25', $result[1]->getDate()->format('Y-m-d'));
    }

    public function testFindByDateRangeReturnsEmptyArrayWhenNothingFound(): void
    {
        $startDate = new DateTimeImmutable('2025-09-24');
        $endDate = new DateTimeImmutable('2025-09-25');

        $result = $this->repository->findByDateRange('EUR', 'USD', $startDate, $endDate);

        $this->assertEmpty($result);
    }

    private function cleanupExchangeRates(): void
    {
        $this->entityManager->createQuery('DELETE FROM Core\Entity\ExchangeRate e WHERE e.id LIKE :testPrefix')
            ->setParameter('testPrefix', 'test-%')
            ->execute();
    }
}
