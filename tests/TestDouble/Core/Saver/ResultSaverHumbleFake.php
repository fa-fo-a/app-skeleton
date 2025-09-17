<?php

declare(strict_types=1);

namespace Tests\TestDouble\Core\Saver;

use Core\Saver\ResultSaverInterface;
use Core\Entity\Result;

class ResultSaverHumbleFake implements ResultSaverInterface
{
    /**
     * @var Result[] $savedEntities
     */
    public array $savedEntities = [];

    public function save(Result $entity): void
    {
        $this->savedEntities[] = $entity;
    }
}
