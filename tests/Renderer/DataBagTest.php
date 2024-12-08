<?php

declare(strict_types=1);

namespace tests\Command\Renderer;

use Calc\Renderer\DataBag;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DataBag::class)]
class DataBagTest extends TestCase
{
    public function testHappyPath(): void
    {
        $dataBag = new DataBag('test');

        $this->assertEquals('test', $dataBag->data);
        $this->assertEquals(0, $dataBag->flags);
    }
}