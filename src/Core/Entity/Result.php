<?php

declare(strict_types=1);

namespace Core\Entity;

class Result
{
    public function __construct(
        private string $uuid,
        private int $result,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getResult(): int
    {
        return $this->result;
    }
}
