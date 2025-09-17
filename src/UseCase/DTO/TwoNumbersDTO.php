<?php

declare(strict_types=1);

namespace UseCase\DTO;

class TwoNumbersDTO
{
    public function __construct(
        private int $firstNumber,
        private int $secondNumber
    ) {
    }

    public function getFirstNumber(): int
    {
        return $this->firstNumber;
    }

    public function getSecondNumber(): int
    {
        return $this->secondNumber;
    }
}
