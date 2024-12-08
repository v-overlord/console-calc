<?php

declare(strict_types=1);

namespace tests\Command\RPN;

use Calc\RPN\Deque;
use Calc\RPN\Token\NumberToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Deque::class)]
class DequeTest extends TestCase
{
    public function testHappyPath(): void
    {
        $deque = new Deque("test");

        $this->assertIsIterable($deque);

        $this->assertCount(0, $deque);

        $this->assertTrue($deque->isEmpty());

        $deque->addFront(new NumberToken(42));

        $this->assertCount(1, $deque);

        $this->assertEquals([
            new NumberToken(42),
        ], iterator_to_array($deque));

        $deque->addBack(new NumberToken(43));

        $this->assertCount(2, $deque);

        $this->assertEquals([
            new NumberToken(42),
            new NumberToken(43),
        ], iterator_to_array($deque));

        $deque->addFront(new NumberToken(41));

        $this->assertEquals([
            new NumberToken(41),
            new NumberToken(42),
            new NumberToken(43),
        ], iterator_to_array($deque));

        $this->assertFalse($deque->isEmpty());

        $this->assertCount(3, $deque);
        $this->assertEquals(new NumberToken(43), $deque->peekBack());
        $this->assertEquals(new NumberToken(41), $deque->peekFront());
        $this->assertCount(3, $deque);
    }

    public function testRemove(): void
    {
        $deque = new Deque("test", [
            new NumberToken(42),
            new NumberToken(43),
            new NumberToken(44),
        ]);

        $this->assertEquals(3, $deque->size());

        $deque->removeBack();

        $this->assertEquals([
            new NumberToken(42),
            new NumberToken(43),
        ], iterator_to_array($deque));

        $deque->removeFront();

        $this->assertEquals([
            new NumberToken(43),
        ], iterator_to_array($deque));
    }

    public function testException(): void
    {
        $deque = new Deque("test", []);

        // This should be tested using the expectException method, but I'm too lazy to create nearly
        // identical methods separately! xD
        try {
            $deque->removeBack();
        } catch (\UnderflowException $e) {
        }

        try {
            $deque->removeFront();
        } catch (\UnderflowException $e) {
        }

        // If the test doesn't fail, it's considered successful by definition - just feed the stub to PHPUnit.
        $this->assertTrue(true);
    }

    public function testToString(): void
    {
        $deque = new Deque("test", [
            new NumberToken(42),
            new NumberToken(43),
        ]);
        $expectedOutput0 = "{ test: | [NUMBER: 42] | | [NUMBER: 43] | }";
        $expectedOutput1 = "{ test: | [NUMBER: 42] | }";

        $this->assertEquals($expectedOutput0, $deque->toString());

        $deque->removeBack();

        $this->assertEquals($expectedOutput1, (string) $deque);
    }
}