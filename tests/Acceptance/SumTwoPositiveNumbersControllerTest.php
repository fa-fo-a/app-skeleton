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
        parent::setUp();
        $this->client = self::createClient();
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

        $this->assertResponseIsSuccessful();

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

        $this->assertResponseStatusCodeSame(400);
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

        $this->assertResponseStatusCodeSame(400);
    }
}
