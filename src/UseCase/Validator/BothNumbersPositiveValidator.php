<?php

declare(strict_types=1);

namespace UseCase\Validator;

use UseCase\DTO\TwoNumbersDTO;
use UseCase\Exception\OneOfTwoNumbersNegativeException;

class BothNumbersPositiveValidator
{
    /**
     * @throws OneOfTwoNumbersNegativeException
     */
    public function validate(TwoNumbersDTO $dto): void
    {
        if ($dto->getFirstNumber() < 0 || $dto->getSecondNumber() < 0) {
            OneOfTwoNumbersNegativeException::throwOneOfTwoNumbersNegative();
        }
    }
}
