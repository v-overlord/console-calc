<?php

namespace Calc\Lexer;

use Calc\Utils\EnumToArray;

enum LexerStates: int
{
    use EnumToArray;

    case NEW = 0;
    case NUMBER = 1;
    case STRING = 2;
    case OPERATOR = 3;
    case BRACKET = 4;
    case COMMA = 5;
    case COMPLETE = 6;
}