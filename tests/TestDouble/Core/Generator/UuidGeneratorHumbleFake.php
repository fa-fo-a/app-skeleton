<?php

declare(strict_types=1);

namespace Tests\TestDouble\Core\Generator;

use Core\Generator\UuidGeneratorInterface;

class UuidGeneratorHumbleFake implements UuidGeneratorInterface
{
    public string $uuid = '';

    public function generate(): string
    {
        return $this->uuid;
    }
}
