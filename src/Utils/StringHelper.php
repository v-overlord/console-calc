<?php

namespace Calc\Utils;

class StringHelper
{
    /**
     * @return \Generator<int, string, null, null>
     */
    public static function iterate(string $input): \Generator
    {
        // strtok has mb problems, so...
        $len = mb_strlen($input, 'UTF-8');
        $i = 0;

        while ($i < $len) {
            yield mb_substr($input, $i, 1, 'UTF-8');
            $i++;
        }
    }

    public static function length(string $input): int
    {
        return mb_strlen($input, 'UTF-8');
    }
}