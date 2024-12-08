<?php

declare(strict_types=1);

namespace tests\Command\RPN\Token;

use Calc\RPN\Token\NumberToken;
use Calc\RPN\Token\OperatorToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OperatorToken::class)]
class OperatorTokenTest extends TestCase
{
    public function testHappyPath(): void
    {
        $existToken = new OperatorToken('+');

        $this->assertEquals('+', $existToken->getRepresentation());
        $this->assertEquals(2, $existToken->getRequiredArguments());
        $this->assertEquals(1, $existToken->getPrecedence());

        $numberTokenAsResult = $existToken->solve([
            new NumberToken(2),
            new NumberToken(2),
        ]);

        $this->assertEqualsWithDelta(4, $numberTokenAsResult->getValue(), 0.1);

        $unaryMinus = new OperatorToken('~');

        $this->assertEquals('~', $unaryMinus->getRepresentation());
        $this->assertEquals(1, $unaryMinus->getRequiredArguments());
        $this->assertEquals(6, $unaryMinus->getPrecedence());

        $numberTokenAsResult = $unaryMinus->solve([
            new NumberToken(2),
        ]);

        $this->assertEqualsWithDelta(-2, $numberTokenAsResult->getValue(), 0.1);

        $minus = new OperatorToken('-');

        $numberTokenAsResult = $minus->solve([
            new NumberToken(2),
            new NumberToken(2),
        ]);

        $this->assertEqualsWithDelta(0, $numberTokenAsResult->getValue(), 0.1);

        $divide = new OperatorToken('/');

        $numberTokenAsResult = $divide->solve([
            new NumberToken(4),
            new NumberToken(2),
        ]);

        $this->assertEqualsWithDelta(2, $numberTokenAsResult->getValue(), 0.1);

        $multiply = new OperatorToken('*');

        $numberTokenAsResult = $multiply->solve([
            new NumberToken(3),
            new NumberToken(2),
        ]);

        $this->assertEqualsWithDelta(6, $numberTokenAsResult->getValue(), 0.1);
    }

    public function testNonExistentOperator(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $nonExistentToken = new OperatorToken('foo');
    }
}