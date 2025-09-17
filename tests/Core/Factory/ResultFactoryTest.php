<?php

declare(strict_types=1);

namespace Tests\Core\Factory;

use Core\Entity\Result;
use Core\Factory\ResultFactory;
use PHPUnit\Framework\TestCase;
use Tests\TestDouble\Core\Generator\UuidGeneratorHumbleFake;

class ResultFactoryTest extends TestCase
{
    private UuidGeneratorHumbleFake $uuidGeneratorFake;

    private ResultFactory $resultFactory;

    protected function setUp(): void
    {
        $this->uuidGeneratorFake = new UuidGeneratorHumbleFake();

        $this->resultFactory = new ResultFactory(
            $this->uuidGeneratorFake
        );
    }

    public function testSuccess(): void
    {
        $uuid = 'testone';
        $result = 42;
        $this->uuidGeneratorFake->uuid = $uuid;

        $this->assertEquals(
            new Result(
                $uuid,
                $result
            ),
            $this->resultFactory->build(
                $result
            )
        );
    }
}
