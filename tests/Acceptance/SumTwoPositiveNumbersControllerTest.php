<?php

declare(strict_types=1);

namespace Tests\Acceptance;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use UI\Http\Controller\SumTwoPositiveNumbersController;

class SumTwoPositiveNumbersControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testSuccess(): void
    {
        $this->client->request(
            'POST',
            SumTwoPositiveNumbersController::ROUTE,
            [],
            [],
            [],
            json_encode(
                [
                    'a' => 5, 'b' => 10
                ],
                JSON_THROW_ON_ERROR
            )
        );

        self::assertResponseIsSuccessful();

        $this->assertJsonStringEqualsJsonString(
            (string) json_encode(['result' => 15], JSON_THROW_ON_ERROR),
            (string) $this->client
                ->getResponse()
                ->getContent()
        );
    }

    public function testRequiresFields(): void
    {
        $this->client->request(
            'POST',
            SumTwoPositiveNumbersController::ROUTE,
            [],
            [],
            [],
            (string) json_encode([])
        );

        self::assertResponseStatusCodeSame(400);
    }

    public function testCannotPassNegativeNumbers(): void
    {
        $this->client->request(
            'POST',
            SumTwoPositiveNumbersController::ROUTE,
            [],
            [],
            [],
            json_encode(
                [
                    'a' => -5, 'b' => 10
                ],
                JSON_THROW_ON_ERROR
            )
        );

        self::assertResponseStatusCodeSame(400);
    }
}
