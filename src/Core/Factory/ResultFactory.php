<?php

declare(strict_types=1);

namespace Core\Factory;

use Core\Entity\Result;
use Core\Generator\UuidGeneratorInterface;

class ResultFactory
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    public function build(int $result): Result
    {
        return new Result(
            uuid: $this->uuidGenerator->generate(),
            result: $result,
        );
    }
}
