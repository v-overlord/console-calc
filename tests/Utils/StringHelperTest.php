<?php

declare(strict_types=1);

namespace tests\Command\Utils;

use Calc\Utils\StringHelper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StringHelper::class)]
class StringHelperTest extends TestCase
{
    public function testHappyPath(): void
    {
        $testString = 'test';
        $testArray = ['t', 'e', 's', 't'];

        $this->assertEquals(4, StringHelper::length($testString));
        $this->assertEquals($testArray, iterator_to_array(StringHelper::iterate($testString)));
    }
}