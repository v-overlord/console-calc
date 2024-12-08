<?php

namespace Calc\RPN\Token;

class CommaToken extends AbstractToken
{
    protected string $tokenName = 'COMMA';

    public function __construct(
        private readonly string $value
    )
    {
    }

    public function getRepresentation(): string
    {
        return $this->value;
    }
}