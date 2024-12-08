<?php

declare(strict_types=1);

namespace tests\Command;

use Calc\Kernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

#[CoversNothing]
class CalculatorCommandTest extends TestCase
{
    public static Application $applicationInstance;

    public static function setUpBeforeClass(): void
    {
        self::$applicationInstance = (new Kernel())->getConsoleApplication();
    }

    public function testHappyPath(): void
    {
        $this->assertEquals(0, 0);
    }

    public function testExecute()
    {
        $command = self::$applicationInstance->find('calc');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            "expression" => "(2+2)*2"
        ]);

        $output = trim($commandTester->getDisplay());
        $statusCode = $commandTester->getStatusCode();

        $this->assertStringContainsString('8', $output);
        $this->assertEquals(0, $statusCode);
    }
}