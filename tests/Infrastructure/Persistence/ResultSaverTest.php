<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence;

use Core\Entity\Result;
use Core\Factory\ResultFactory;
use Core\Exception\SaveException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Infrastructure\Persistence\ResultSaver;

class ResultSaverTest extends KernelTestCase
{
    private ResultFactory $factory;

    private EntityManagerInterface $entityManager;

    private ResultSaver $saver;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->factory = $container->get(ResultFactory::class);

        $this->saver = new ResultSaver(
            $this->entityManager
        );
    }

    public function testSuccessSave(): void
    {
        $result = $this->factory->build(42);

        $this->saver->save($result);

        $savedResult = $this->entityManager
            ->getRepository(Result::class)
            ->find($result->getUuid())
        ;

        $this->assertEquals(
            $result,
            $savedResult
        );
    }

    public function testEntityManagerThrowsException(): void
    {
        $this->expectException(SaveException::class);

        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntityManager->expects($this->once())
            ->method('flush')
            ->will($this->throwException(new \Exception('Flush failed')));

        $saver = new ResultSaver($mockEntityManager);

        $saver->save($this->factory->build(42));
    }
}
