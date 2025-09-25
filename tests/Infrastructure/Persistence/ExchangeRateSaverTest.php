<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence;

use Core\Entity\ExchangeRate;
use Core\Exception\SaveException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Infrastructure\Persistence\ExchangeRateSaver;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Exception;

class ExchangeRateSaverTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private ExchangeRateSaver $saver;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);

        $this->saver = new ExchangeRateSaver(
            $this->entityManager
        );
    }

    public function testSuccessSave(): void
    {
        $exchangeRate = new ExchangeRate(
            'test-id-' . uniqid(),
            'USD',
            'EUR',
            0.85,
            new DateTimeImmutable()
        );

        $this->saver->save($exchangeRate);

        $savedExchangeRate = $this->entityManager
            ->getRepository(ExchangeRate::class)
            ->find($exchangeRate->getId())
        ;

        $this->assertNotNull($savedExchangeRate);
        $this->assertSame($exchangeRate->getId(), $savedExchangeRate->getId());
        $this->assertSame($exchangeRate->getCurrencyFrom(), $savedExchangeRate->getCurrencyFrom());
        $this->assertSame($exchangeRate->getCurrencyTo(), $savedExchangeRate->getCurrencyTo());
        $this->assertSame($exchangeRate->getRate(), $savedExchangeRate->getRate());
    }

    public function testEntityManagerThrowsException(): void
    {
        $this->expectException(SaveException::class);

        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager->expects($this->once())
            ->method('flush')
            ->will($this->throwException(new Exception('Flush failed')));

        $saver = new ExchangeRateSaver($mockEntityManager);

        $exchangeRate = new ExchangeRate(
            'test-id',
            'USD',
            'EUR',
            0.85,
            new DateTimeImmutable()
        );

        $saver->save($exchangeRate);
    }
}
