<?php

declare(strict_types=1);

namespace tests\Command\RPN;

use Calc\Lexer\Token;
use Calc\Lexer\TokenStream;
use Calc\Lexer\TokenType;
use Calc\RPN\RPNException;
use Calc\RPN\SolveException;
use Calc\RPN\Token\BracketToken;
use Calc\RPN\Token\CommaToken;
use Calc\RPN\Token\ConstToken;
use Calc\RPN\Token\FunctionToken;
use Calc\RPN\Token\NumberToken;
use Calc\RPN\Token\OperatorToken;
use Calc\RPN\TokenSpecializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TokenSpecializer::class)]
class TokenSpecializerTest extends TestCase
{
    public function testHappyPath(): void
    {
        $lexerTokenStream = new TokenStream([
            new Token(TokenType::STRING, 'sin'),
            new Token(TokenType::BRACKET, '('),
            new Token(TokenType::NUMERIC, '2'),
            new Token(TokenType::OPERATOR, '+'),
            new Token(TokenType::NUMERIC, '2'),
            new Token(TokenType::COMMA, ','),
            new Token(TokenType::NUMERIC, '3'),
            new Token(TokenType::BRACKET, ')'),
            new Token(TokenType::OPERATOR, '-'),
            new Token(TokenType::STRING, 'e'),
            new Token(TokenType::OPERATOR, '*'),
            new Token(TokenType::STRING, 'pi'),
        ]);

        $tokenSpecializer = new TokenSpecializer($lexerTokenStream);

        $resultStream = $tokenSpecializer->specializeRawTokens();

        $this->assertIsIterable($resultStream);

        $this->assertEquals([
            new FunctionToken('sin'),
            new BracketToken('('),
            new NumberToken(2),
            new OperatorToken('+'),
            new NumberToken(2),
            new CommaToken(','),
            new NumberToken(3),
            new BracketToken(')'),
            new OperatorToken('-'),
            new ConstToken('e'),
            new OperatorToken('*'),
            new ConstToken('pi')

        ], iterator_to_array($resultStream));
    }

    public function testUnknownString(): void
    {
        $this->expectException(RPNException::class);
        $lexerTokenStream = new TokenStream([
            new Token(TokenType::STRING, '-'),
        ]);

        $tokenSpecializer = new TokenSpecializer($lexerTokenStream);

        $tokenSpecializer->specializeRawTokens();
    }

    public function testConstAndBracket(): void
    {
        $lexerTokenStream = new TokenStream([
            new Token(TokenType::STRING, 'foo'),
            new Token(TokenType::BRACKET, '}'),
        ]);

        $tokenSpecializer = new TokenSpecializer($lexerTokenStream);

        $this->assertEquals([
           new ConstToken('foo'),
           new BracketToken('}'),
        ], iterator_to_array($tokenSpecializer->specializeRawTokens()));
    }
}