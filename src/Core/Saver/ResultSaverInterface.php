<?php

declare(strict_types=1);

namespace Core\Saver;

use Core\Entity\Result;

interface ResultSaverInterface
{
    /**
     * @throws SaveException
     */
    public function save(Result $result): void;
}
