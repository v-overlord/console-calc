<?php

namespace Calc\Lexer;

class Exception extends \Exception
{
    private string $messageFormat = "%s at position %d";
    public int $position;

    public function __construct(string $message, int $position)
    {
        $this->position = $position;
        parent::__construct($message);
    }

    public function toString(): string
    {
        return sprintf($this->messageFormat, $this->message, $this->position);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}