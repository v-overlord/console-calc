<?php

namespace Calc\RPN\Token;

enum NumberBase: string
{
    case DEC = 'dec';
}

class NumberToken extends AbstractToken
{
    protected string $tokenName = 'NUMBER';

    private NumberBase $base;

    public function __construct(
        private float $value
    )
    {
        $this->determineBase();
    }

    private function determineBase(): void
    {
        // @TODO: Implement me
        $this->base = NumberBase::DEC;
    }

    public function getRepresentation(): string
    {
        return (string)$this->value;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}