<?php

declare(strict_types=1);

namespace tests;

use Calc\Kernel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;

#[CoversClass(Kernel::class)]
class KernelTest extends TestCase
{
    public function testHappyPath(): void
    {
        $kernel = new Kernel();
        $this->assertTrue($kernel->getConsoleApplication() instanceof Application);
    }
}