<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use Throwable;
use Core\Entity\Result;
use Core\Exception\SaveException;
use Core\Saver\ResultSaverInterface;
use Doctrine\ORM\EntityManagerInterface;

class ResultSaver implements ResultSaverInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Result $result): void
    {
        try {
            $this->entityManager->persist($result);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            SaveException::throwCannotSave($e);
        }
    }
}
