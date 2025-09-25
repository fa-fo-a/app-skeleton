<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Core\Entity\ExchangeRate;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use UI\Http\Controller\ExchangeRatesController;

class ExchangeRatesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->cleanupTestData();
    }

    protected function tearDown(): void
    {
        $this->cleanupTestData();
        parent::tearDown();
    }

    public function testSuccess(): void
    {
        $today = new DateTimeImmutable();
        $yesterday = $today->modify('-1 day');

        $exchangeRate1 = new ExchangeRate('test-1', 'USD', 'EUR', 0.85, $today);
        $exchangeRate2 = new ExchangeRate('test-2', 'USD', 'EUR', 0.86, $yesterday);

        $this->entityManager->persist($exchangeRate1);
        $this->entityManager->persist($exchangeRate2);
        $this->entityManager->flush();

        $this->client->request('GET', '/exchange-rates/USD/EUR');

        self::assertResponseIsSuccessful();

        $content = $this->client->getResponse()->getContent();
        $this->assertNotFalse($content);

        /** @var array<string, mixed> $data */
        $data = json_decode($content, true);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('count', $data);
        $this->assertSame(2, $data['count']);

        /** @var array<int, array<string, mixed>> $dataArray */
        $dataArray = $data['data'];
        $this->assertCount(2, $dataArray);

        $this->assertSame('USD', $dataArray[0]['currency_from']);
        $this->assertSame('EUR', $dataArray[0]['currency_to']);
        $this->assertArrayHasKey('rate', $dataArray[0]);
        $this->assertArrayHasKey('date', $dataArray[0]);
    }

    public function testInvalidCurrencyFromParameter(): void
    {
        $this->client->request('GET', '/exchange-rates/US/EUR');

        self::assertResponseStatusCodeSame(400);

        $content = $this->client->getResponse()->getContent();
        $this->assertNotFalse($content);

        /** @var array<string, mixed> $data */
        $data = json_decode($content, true);
        $this->assertArrayHasKey('errors', $data);
        /** @var string[] $errors */
        $errors = $data['errors'];
        $this->assertContains('Invalid currency_from parameter. Must be 3-character currency code.', $errors);
    }

    public function testInvalidCurrencyToParameter(): void
    {
        $this->client->request('GET', '/exchange-rates/USD/EU');

        self::assertResponseStatusCodeSame(400);

        $content = $this->client->getResponse()->getContent();
        $this->assertNotFalse($content);

        /** @var array<string, mixed> $data */
        $data = json_decode($content, true);
        $this->assertArrayHasKey('errors', $data);
        /** @var string[] $errors */
        $errors = $data['errors'];
        $this->assertContains('Invalid currency_to parameter. Must be 3-character currency code.', $errors);
    }

    public function testEmptyResult(): void
    {
        $this->client->request('GET', '/exchange-rates/EUR/JPY');

        self::assertResponseIsSuccessful();

        $content = $this->client->getResponse()->getContent();
        $this->assertNotFalse($content);

        /** @var array<string, mixed> $data */
        $data = json_decode($content, true);
        $this->assertSame(0, $data['count']);
        $this->assertEmpty($data['data']);
    }

    private function cleanupTestData(): void
    {
        $this->entityManager->createQuery('DELETE FROM Core\Entity\ExchangeRate e WHERE e.id LIKE :testPrefix')
            ->setParameter('testPrefix', 'test-%')
            ->execute();
    }
}
