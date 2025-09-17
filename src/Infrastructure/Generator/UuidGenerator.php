<?php

declare(strict_types=1);

namespace Infrastructure\Generator;

use Core\Generator\UuidGeneratorInterface;
use Symfony\Component\Uid\UuidV7;

class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return UuidV7::v7()->toRfc4122();
    }
}
