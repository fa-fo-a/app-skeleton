<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Console\Command;

use Infrastructure\Console\Command\LoadExchangeRatesCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use UseCase\LoadExchangeRatesHandler;

class LoadExchangeRatesCommandTest extends TestCase
{
    private LoadExchangeRatesHandler&MockObject $mockHandler;
    private LoadExchangeRatesCommand $command;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->mockHandler = $this->createMock(LoadExchangeRatesHandler::class);
        $this->command = new LoadExchangeRatesCommand($this->mockHandler);

        $application = new Application();
        $application->add($this->command);

        $this->commandTester = new CommandTester($this->command);
    }

    public function testExecuteSuccess(): void
    {
        $expectedCount = 1680;

        $this->mockHandler->expects($this->once())
            ->method('handle')
            ->willReturn($expectedCount);

        $exitCode = $this->commandTester->execute([]);

        $this->assertSame(Command::SUCCESS, $exitCode);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Loading Exchange Rates', $output);
        $this->assertStringContainsString("Successfully loaded $expectedCount exchange rates", $output);
    }
}
