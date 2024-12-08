<?php

namespace Calc\RPN\Token;

class BracketToken extends AbstractToken
{
    private const INIT_CALL_BRACKET = '(';

    private const OPEN_BRACKET = '({[';

    protected string $tokenName = 'BRACKET';

    public function __construct(
        private readonly string $value
    )
    {
    }

    public function canBeCallBracket(): bool
    {
        return $this->value === self::INIT_CALL_BRACKET;
    }

    public function isOpenBracket(): bool
    {
        return str_contains(self::OPEN_BRACKET, $this->value);
    }

    public function getRepresentation(): string
    {
        return $this->value;
    }
}