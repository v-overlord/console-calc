<?php

declare(strict_types=1);

namespace tests\Command\RPN;

use Calc\Lexer\Token;
use Calc\Lexer\TokenType;
use Calc\RPN\RPNException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RPNException::class)]
class RPNExceptionTest extends TestCase
{
    public function testHappyPath(): void
    {
        $exception = new RPNException('test', new Token(TokenType::NUMERIC, '42'));
        $expectedToString = "test [raw: NUMERIC [42]]";

        $this->assertTrue($exception instanceof \Exception);

        $this->assertEquals($expectedToString, $exception->toString());
        $this->assertEquals($expectedToString, (string) $exception);
    }
}