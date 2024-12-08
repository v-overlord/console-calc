<?php

declare(strict_types=1);

namespace tests\Command\Lexer;

use Calc\Lexer\Exception;
use Calc\Lexer\Lexer;
use Calc\Lexer\Token;
use Calc\Lexer\TokenType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Lexer::class)]
class LexerTest extends TestCase
{
    public function testHappyPath(): void
    {
        $lexer = new Lexer("2 + 2");

        $expectedTokens = [
            new Token(TokenType::NUMERIC, '2'),
            new Token(TokenType::OPERATOR, '+'),
            new Token(TokenType::NUMERIC, '2'),
        ];
        $parsedTokens = iterator_to_array($lexer->parseAndGetTokenStream());

        $this->assertEquals($expectedTokens, $parsedTokens);
    }

    public function testStrings(): void
    {
        $lexer = new Lexer("bar + foo(2, 3)");

        $expectedTokens = [
            new Token(TokenType::STRING, 'bar'),
            new Token(TokenType::OPERATOR, '+'),
            new Token(TokenType::STRING, 'foo'),
            new Token(TokenType::BRACKET, '('),
            new Token(TokenType::NUMERIC, '2'),
            new Token(TokenType::COMMA, ','),
            new Token(TokenType::NUMERIC, '3'),
            new Token(TokenType::BRACKET, ')'),
        ];
        $parsedTokens = iterator_to_array($lexer->parseAndGetTokenStream());

        $this->assertEquals($expectedTokens, $parsedTokens);
    }

    public function testUnknownChar(): void
    {
        $this->expectException(Exception::class);
        $lexer = new Lexer("%");

        $lexer->parseAndGetTokenStream();
    }
}