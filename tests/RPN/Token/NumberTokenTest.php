<?php

declare(strict_types=1);

namespace tests\Command\RPN\Token;

use Calc\RPN\Token\NumberToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NumberToken::class)]
class NumberTokenTest extends TestCase
{
    public function testHappyPath(): void
    {
        $token = new NumberToken(42);

        $this->assertEquals('42', $token->getRepresentation());
        $this->assertEquals(42, $token->getValue());
    }
}