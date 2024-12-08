<?php

declare(strict_types=1);

namespace tests\Command\RPN\Token;

use Calc\RPN\Token\BracketToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BracketToken::class)]
class BracketTokenTest extends TestCase
{
    public function testHappyPath(): void
    {
        $token = new BracketToken('[');
        $toStringValue = "[BRACKET: []";

        $this->assertEquals($toStringValue, $token->toString());
        $this->assertEquals($toStringValue, (string)$token );

        $this->assertEquals('[', $token->getRepresentation());

        $this->assertTrue((new BracketToken('{'))->isOpenBracket());
        $this->assertFalse((new BracketToken('}'))->isOpenBracket());

        $this->assertTrue((new BracketToken('('))->canBeCallBracket());
        $this->assertFalse((new BracketToken('{'))->canBeCallBracket());
    }
}