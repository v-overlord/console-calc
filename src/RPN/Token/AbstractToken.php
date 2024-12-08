<?php

namespace Calc\RPN\Token;

abstract class AbstractToken
{
    private string $toStringFormat = "[%s: %s]";

    protected string $tokenName = '[ABSTRACT]';

    public function toString(): string
    {
        return sprintf($this->toStringFormat, $this->tokenName, $this->getRepresentation());
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    abstract public function getRepresentation(): string;
}