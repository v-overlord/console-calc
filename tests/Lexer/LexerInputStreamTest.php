<?php

declare(strict_types=1);

namespace tests\Command\Lexer;

use Calc\Lexer\LexerInputStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LexerInputStream::class)]
class LexerInputStreamTest extends TestCase
{
    public function testHappyPath(): void
    {
        $inputStream = new LexerInputStream("abc");

        $this->assertFalse($inputStream->isEOF());

        $this->assertEquals(0, $inputStream->getCurrentPosition());
        $this->assertEquals('a', $inputStream->current());
        $this->assertEquals('b', $inputStream->peek());

        $this->assertNotNull($inputStream->next());

        $this->assertEquals(1, $inputStream->getCurrentPosition());
        $this->assertEquals('b', $inputStream->current());
        $this->assertEquals('c', $inputStream->peek());

        $this->assertNotNull($inputStream->next());

        $this->assertEquals(2, $inputStream->getCurrentPosition());
        $this->assertEquals('c', $inputStream->current());
        $this->assertNull($inputStream->peek());

        $inputStream->next();
        $this->assertNull($inputStream->next());
    }
}