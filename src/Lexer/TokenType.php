<?php

namespace Calc\Lexer;

enum TokenType: int
{
    case UNKNOWN = 0;
    case NUMERIC = 1;
    case STRING = 2;

    case OPERATOR = 3;

    case BRACKET = 4;

    case COMMA = 5;

    public function toString(): string
    {
        return match ($this) {
            self::UNKNOWN => "UNKNOWN",
            self::NUMERIC => "NUMERIC",
            self::STRING => "STRING",
            self::OPERATOR => "OPERATOR",
            self::BRACKET => "BRACKET",
            self::COMMA => "COMMA",
        };
    }
}