<?php

declare(strict_types=1);

namespace Tests\TestDouble\Core\Factory;

use Core\Factory\ResultFactory;
use Core\Entity\Result;

class ResultFactoryHumbleFake extends ResultFactory
{
    public string $uuid = '';

    public function __construct()
    {
    }

    public function build(int $result): Result
    {
        return new Result(
            uuid: $this->uuid,
            result: $result,
        );
    }
}
