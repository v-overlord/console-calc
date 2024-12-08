<?php

declare(strict_types=1);

namespace tests\Command\FSA;

use Calc\FSA\FiniteStateAutomaton;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FiniteStateAutomaton::class)]
class FiniteStateAutomatonTest extends TestCase
{
    private FiniteStateAutomaton $finiteStateAutomaton;

    public function setUp(): void
    {
        $this->finiteStateAutomaton = new FiniteStateAutomaton([
            0, 1
        ], [
            0 => [1],
        ], 0);
    }

    public function testHappyPath(): void
    {
        $this->assertEquals(0, $this->finiteStateAutomaton->getCurrentState());

        $this->finiteStateAutomaton->transit(1);
        $this->finiteStateAutomaton->tick();

        $this->assertEquals(1, $this->finiteStateAutomaton->getCurrentState());

        $this->finiteStateAutomaton->transit(0);
        $this->finiteStateAutomaton->tick();

        $this->assertEquals(1, $this->finiteStateAutomaton->getCurrentState());
    }
}