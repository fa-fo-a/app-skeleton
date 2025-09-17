<?php

declare(strict_types=1);

namespace Core\Saver;

use Core\Entity\Result;
use Core\Exception\SaveException;

interface ResultSaverInterface
{
    /**
     * @throws SaveException
     */
    public function save(Result $result): void;
}
