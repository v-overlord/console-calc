<?php

declare(strict_types=1);

namespace tests\Command\Lexer;

use Calc\Lexer\Token;
use Calc\Lexer\TokenStream;
use Calc\Lexer\TokenType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TokenStream::class)]
class TokenStreamTest extends TestCase
{
    public function testHappyPath(): void
    {
        $tokenStream = new TokenStream([
            new Token(TokenType::OPERATOR, '42'),
            new Token(TokenType::STRING, '+'),
        ]);

        $this->assertCount(2, $tokenStream);
        $this->assertIsIterable($tokenStream);

        foreach ($tokenStream as $token) {
            $this->assertTrue($token instanceof Token);
        }

        $tokenStream->pushToken(new Token(TokenType::BRACKET, '{'));

        $this->assertCount(3, $tokenStream);

        foreach ($tokenStream->stream() as $token) {
            $this->assertTrue($token instanceof Token);
        }
    }
}