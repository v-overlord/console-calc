<?php

declare(strict_types=1);

namespace tests\Command\RPN\Token;

use Calc\RPN\SolveException;
use Calc\RPN\Token\ConstToken;
use Calc\RPN\Token\NumberToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConstToken::class)]
class ConstTokenTest extends TestCase
{
    public function testHappyPath(): void
    {
        $existToken = new ConstToken('pi');

        $this->assertEquals('pi', $existToken->getRepresentation());
        $this->assertEquals('pi', $existToken->getConstName());

        $numberTokenAsResult = $existToken->solve();

        $this->assertTrue($numberTokenAsResult instanceof NumberToken);

        $this->assertEqualsWithDelta(3.1415, $numberTokenAsResult->getValue(), 0.001);
    }

    public function testInvalidConst(): void
    {
        $invalidToken = new ConstToken('-bar');
        $this->assertFalse($invalidToken->isValid());
    }

    public function testNonExistConst(): void
    {
        $this->expectException(SolveException::class);

        $nonExistToken = new ConstToken('foo');

        $nonExistToken->solve();
    }
}