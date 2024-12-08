<?php

namespace Calc\Utils;

class LookupTable
{
    /**
     * @var array<int, bool>
     */
    private array $sparseTable;

    public function __construct(
        private string $charSet,
    )
    {
        $this->fillTable();
    }

    public function check(string $char): bool
    {
        return isset($this->sparseTable[mb_ord($char)]);
    }

    private function fillTable(): void
    {
        foreach (StringHelper::iterate($this->charSet) as $char) {
            $this->sparseTable[mb_ord($char)] = true;
        }
    }
}