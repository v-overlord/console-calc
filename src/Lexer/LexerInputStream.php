<?php

namespace Calc\Lexer;

use Calc\Utils\StringHelper;

class LexerInputStream
{
    private int $inputLength;
    private int $currentPosition = 0;

    public function __construct(
        private readonly string $input,
    )
    {
        $this->inputLength = StringHelper::length($this->input);
    }

    public function next(): ?int
    {
        // For some unknown reason, PHPUnit gets stuck in an infinite loop when we check "$this->inputLength - 1" or
        // "$this->currentPosition + 1". To avoid this, we simply let the position increment, as
        // the other methods are already handling access checks properly.
        if ($this->currentPosition < $this->inputLength) {

            $this->currentPosition++;
            return $this->currentPosition;
        }

        return null;
    }

    public function current(): ?string
    {
        return $this->currentPosition < $this->inputLength ? $this->input[$this->currentPosition] : null;
    }

    public function peek(): ?string
    {
        return $this->currentPosition + 1 < $this->inputLength ? $this->input[$this->currentPosition + 1] : null;
    }

    public function isEOF(): bool
    {
        return $this->currentPosition >= $this->inputLength;
    }

    public function getCurrentPosition(): int
    {
        return $this->currentPosition;
    }
}