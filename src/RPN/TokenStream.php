<?php

namespace Calc\RPN;

use Calc\RPN\Token\AbstractToken;
use Traversable;

// Well, since PHP lacks generics, I'll need to duplicate this iterator. I want to keep the type hints intact and avoid mixing different types.
class TokenStream implements \IteratorAggregate, \Countable
{
    public function __construct(
        /**
         * @var array<AbstractToken>
         */
        private array $tokens = [],
    )
    {
    }

    public function pushToken(AbstractToken $token): TokenStream
    {
        $this->tokens[] = $token;

        return $this;
    }

    /**
     * @return \Generator<int, AbstractToken, AbstractToken, AbstractToken>
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