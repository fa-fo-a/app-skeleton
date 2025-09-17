<?php

declare(strict_types=1);

namespace Tests\UseCase;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\TestDouble\Core\Factory\ResultFactoryHumbleFake;
use Tests\TestDouble\Core\Saver\ResultSaverHumbleFake;
use UseCase\SumTwoPositiveNumbersHandler;
use UseCase\DTO\TwoNumbersDTO;
use UseCase\Exception\OneOfTwoNumbersNegativeException;

class SumTwoPositiveNumbersHandlerTest extends TestCase
{
    private ResultFactoryHumbleFake $resultFactory;
    private ResultSaverHumbleFake $resultSaver;

    private SumTwoPositiveNumbersHandler $handler;

    protected function setUp(): void
    {
        $this->resultFactory = new ResultFactoryHumbleFake();
        $this->resultSaver = new ResultSaverHumbleFake();

        $this->handler = new SumTwoPositiveNumbersHandler(
            $this->resultFactory,
            $this->resultSaver
        );
    }

    public function testSuccess(): void
    {
        $this->resultFactory->uuid = 'uuid';
        $expectedResult = $this->resultFactory->build(8);

        $actualResult = $this->handler->handle(
            new TwoNumbersDTO(
                3,
                5
            )
        );

        $this->assertEquals(
            $expectedResult,
            $actualResult
        );

        $this->assertContainsEquals(
            $expectedResult,
            $this->resultSaver->savedEntities
        );
    }

    #[DataProvider('dataOneOfNumbersNegative')]
    public function testOneOfNumbersNegative(
        int $firstNumber,
        int $secondNumber
    ): void {
        $this->expectExceptionMessage(OneOfTwoNumbersNegativeException::MESSAGE);
        $this->expectException(OneOfTwoNumbersNegativeException::class);

        $this->handler->handle(
            new TwoNumbersDTO(
                $firstNumber,
                $secondNumber
            )
        );
    }

    /**
     * @return array<string, array<int>>
     */
    public static function dataOneOfNumbersNegative(): array
    {
        return [
            'first number negative' => [
                -3,
                5
            ],
            'second number negative' => [
                3,
                -5
            ],
            'both numbers negative' => [
                -3,
                -5
            ],
        ];
    }
}
