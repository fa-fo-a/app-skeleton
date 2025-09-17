<?php

declare(strict_types=1);

namespace Core\Generator;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
