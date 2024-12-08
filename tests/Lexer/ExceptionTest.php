<?php

declare(strict_types=1);

namespace tests\Command\Lexer;

use Calc\Lexer\Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Exception::class)]
class ExceptionTest extends TestCase
{
    public function testHappyPath(): void
    {
        $exception = new Exception("foo", 0);

        $this->assertTrue($exception instanceof \Exception);
        $this->assertEquals("foo at position 0", (string)$exception);
        $this->assertEquals("foo at position 0", $exception->toString());
    }
}