<?php

declare(strict_types=1);

namespace tests\Command\Lexer;

use Calc\Lexer\Token;
use Calc\Lexer\TokenType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Token::class)]
class TokenTest extends TestCase
{
    public function testHappyPath(): void
    {
        $token = new Token(TokenType::NUMERIC, '42');

        $this->assertEquals("NUMERIC [42]", $token->toString());

        $token->setType(TokenType::STRING);

        $this->assertEquals("STRING [42]", $token->toString());
        $this->assertEquals("STRING [42]", (string) $token);

        $this->assertEquals(TokenType::STRING, $token->getTokenType());

        $this->assertEquals(42, $token->getTokenValue());
    }

    public function testUnspecified(): void
    {
        $token = Token::unspecified();

        $this->assertEquals('UNKNOWN []', $token->toString());
    }

    public function testAddValue(): void
    {
        $token = new Token(TokenType::NUMERIC, '42');
        $this->assertEquals("NUMERIC [42]", $token->toString());

        $token->addToValue("3");
        $this->assertEquals("NUMERIC [423]", $token->toString());
    }
}