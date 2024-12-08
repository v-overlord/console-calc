<?php

namespace Calc\Lexer;

class Token implements \Stringable
{
    private string $toStringFormat = "%s [%s]";

    public function __construct(
        private TokenType $tokenType,
        private string    $tokenValue,
    )
    {
    }

    public static function unspecified(): Token
    {
        return new self(TokenType::UNKNOWN, '');
    }

    public function setType(TokenType $tokenType): Token
    {
        $this->tokenType = $tokenType;

        return $this;
    }

    public function addToValue(string $value): Token
    {
        $this->tokenValue .= $value;

        return $this;
    }

    public function getTokenType(): TokenType
    {
        return $this->tokenType;
    }

    public function getTokenValue(): string
    {
        return $this->tokenValue;
    }

    public function toString(): string
    {
        return sprintf($this->toStringFormat, $this->tokenType->name, $this->tokenValue);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}