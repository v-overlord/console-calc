<?php

declare(strict_types=1);

namespace tests\Command\RPN\Token;

use Calc\RPN\SolveException;
use Calc\RPN\Token\FunctionToken;
use Calc\RPN\Token\NumberToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FunctionToken::class)]
class FunctionTokenTest extends TestCase
{
    public function testHappyPath(): void
    {
        $existToken = new FunctionToken('pow');

        $this->assertEquals('pow', $existToken->getRepresentation());
        $this->assertEquals(2, $existToken->getRequiredArguments());

        $numberTokenAsResult = $existToken->solve([
            new NumberToken(2),
            new NumberToken(8),
        ]);

        $this->assertTrue($numberTokenAsResult instanceof NumberToken);

        $this->assertEqualsWithDelta(256, $numberTokenAsResult->getValue(), 0.1);
    }

    public function testNonExistentFunction(): void
    {
        $this->expectException(SolveException::class);

        $nonExistentToken = new FunctionToken('foo');
    }
}