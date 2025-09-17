<?php

declare(strict_types=1);

namespace UseCase;

use Core\Factory\ResultFactory;
use Core\Saver\ResultSaverInterface;
use UseCase\DTO\TwoNumbersDTO;
use UseCase\Validator\BothNumbersPositiveValidator;

class SumTwoPositiveNumbersHandler
{
    public function __construct(
        private ResultFactory $resultFactory,
        private ResultSaverInterface $resultSaver,
        private BothNumbersPositiveValidator $validator = new BothNumbersPositiveValidator(),
    ) {
    }

    public function handle(TwoNumbersDTO $dto)
    {
        $this->validator->validate($dto);

        $sum = $dto->getFirstNumber() + $dto->getSecondNumber();

        $result = $this->resultFactory->build($sum);

        $this->resultSaver->save($result);

        return $result;
    }
}
