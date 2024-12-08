<?php

declare(strict_types=1);

namespace tests\Command\RPN;

use Calc\RPN\Token\NumberToken;
use Calc\RPN\TokenStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TokenStream::class)]
class TokenStreamTest extends TestCase
{
    public function testHappyPath(): void
    {
        $stream = new TokenStream([
            new NumberToken(42)
        ]);

        $this->assertCount(1, $stream);

        $this->assertEquals([
            new NumberToken(42),
        ], iterator_to_array($stream));

        foreach ($stream->stream() as $token) {
            $this->assertTrue($token instanceof NumberToken);
        }

        $stream->pushToken(new NumberToken(43));

        $this->assertCount(2, $stream);

        $this->assertEquals([
            new NumberToken(42),
            new NumberToken(43),
        ], iterator_to_array($stream));
    }
}