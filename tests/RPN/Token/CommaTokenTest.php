<?php

declare(strict_types=1);

namespace tests\Command\RPN\Token;

use Calc\RPN\Token\CommaToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CommaToken::class)]
class CommaTokenTest extends TestCase
{
    public function testHappyPath(): void
    {
        $token = new CommaToken(',');

        $this->assertEquals(',', $token->getRepresentation());
    }
}