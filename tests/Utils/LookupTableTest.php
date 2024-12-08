<?php

declare(strict_types=1);

namespace tests\Command\Utils;

use Calc\Utils\LookupTable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LookupTable::class)]
class LookupTableTest extends TestCase
{
    public function testHappyPath(): void
    {
        $lookUpTable = new LookupTable('123');

        $this->assertTrue($lookUpTable->check('1'));
        $this->assertFalse($lookUpTable->check('a'));
    }
}