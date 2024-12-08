<?php

declare(strict_types=1);

namespace tests\Command\Lexer;

use Calc\Lexer\TokenType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TokenType::class)]
class TokenTypeTest extends TestCase
{
    public function testHappyPath(): void
    {
        $tokenType = TokenType::UNKNOWN;
        $this->assertEquals('UNKNOWN', $tokenType->toString());

        $tokenType = TokenType::NUMERIC;
        $this->assertEquals('NUMERIC', $tokenType->toString());

        $tokenType = TokenType::STRING;
        $this->assertEquals('STRING', $tokenType->toString());

        $tokenType = TokenType::OPERATOR;
        $this->assertEquals('OPERATOR', $tokenType->toString());

        $tokenType = TokenType::BRACKET;
        $this->assertEquals('BRACKET', $tokenType->toString());
    }
}