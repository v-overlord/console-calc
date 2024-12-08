<?php

declare(strict_types=1);

namespace tests\Command\RPN;

use Calc\Lexer\Token;
use Calc\Lexer\TokenStream;
use Calc\Lexer\TokenType;
use Calc\RPN\RPN;
use Calc\RPN\SolveException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RPN::class)]
class RPNTest extends TestCase
{
    public function testHappyPath(): void
    {
        $lexerTokenStream = new TokenStream([
            new Token(TokenType::NUMERIC, '2'),
            new Token(TokenType::OPERATOR, '+'),
            new Token(TokenType::NUMERIC, '2'),
            new Token(TokenType::OPERATOR, '+'),
            new Token(TokenType::STRING, 'pow'),
            new Token(TokenType::BRACKET, '('),
            new Token(TokenType::NUMERIC, '2'),
            new Token(TokenType::COMMA, ','),
            new Token(TokenType::NUMERIC, '8'),
            new Token(TokenType::BRACKET, ')'),
            new Token(TokenType::OPERATOR, '-'),
            new Token(TokenType::STRING, 'pi'),
        ]);

        $rpn = new RPN($lexerTokenStream);

        $this->assertEqualsWithDelta(256.8584, $rpn->solve(), 0.0001);
    }

    public function testIsNotEnoughArguments(): void
    {
        $this->expectException(SolveException::class);

        $lexerTokenStream = new TokenStream([
            new Token(TokenType::NUMERIC, '2'),
            new Token(TokenType::OPERATOR, '+'),
        ]);

        $rpn = new RPN($lexerTokenStream);

        $rpn->solve();
    }

    public function testIsMalformed(): void
    {
        $this->expectException(SolveException::class);

        $lexerTokenStream = new TokenStream([
            new Token(TokenType::NUMERIC, '2'),
            new Token(TokenType::NUMERIC, '1'),
            new Token(TokenType::OPERATOR, '~'),
        ]);

        $rpn = new RPN($lexerTokenStream);

        $rpn->solve();
    }
}