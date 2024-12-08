<?php

namespace Calc\Lexer;

use Traversable;

class TokenStream implements \IteratorAggregate, \Countable
{
    public function __construct(
        /**
         * @var array<Token>
         */
        private array $tokens = [],
    )
    {
    }

    public function pushToken(Token $token): TokenStream
    {
        $this->tokens[] = $token;

        return $this;
    }

    /**
     * @return \Generator<int, Token, Token, Token>
     */
    public function stream(): \Generator
    {
        foreach ($this->tokens as $token) {
            yield $token;
        }
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->tokens);
    }

    public function count(): int
    {
        return count($this->tokens);
    }
}