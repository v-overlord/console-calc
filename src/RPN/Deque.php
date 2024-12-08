<?php

namespace Calc\RPN;

use Calc\RPN\Token\AbstractToken;
use Traversable;

class Deque implements \Stringable, \IteratorAggregate, \Countable
{
    private string $toStringFormatString = "{ %s: %s }";
    private string $toStringItemFormatString = "| %s |";


    public function __construct(
        private string $dequeName,
        /**
         * @var array<AbstractToken>
         */
        private array  $items = [],
    )
    {
    }

    public function addFront(AbstractToken $item): void
    {
        array_unshift($this->items, $item);
    }

    public function addBack(AbstractToken $item): void
    {
        $this->items[] = $item;
    }

    public function removeFront(): AbstractToken
    {
        if ($this->isEmpty()) {
            $this->throwEmptyException();
        }
        return array_shift($this->items);
    }

    public function removeBack(): AbstractToken
    {
        if ($this->isEmpty()) {
            $this->throwEmptyException();
        }
        return array_pop($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function peekFront(): ?AbstractToken
    {
        return $this->items[0] ?? null;
    }

    public function peekBack(): ?AbstractToken
    {
        return $this->items[count($this->items) - 1] ?? null;
    }

    public function size(): int
    {
        return count($this->items);
    }

    private function throwEmptyException(): void
    {
        throw new \UnderflowException("Deque is empty");
    }

    public function toString(): string
    {
        $itemRepresentations = [];
        foreach ($this->items as $item) {
            $itemRepresentations[] = sprintf($this->toStringItemFormatString, $item->toString());
        }

        return sprintf($this->toStringFormatString, $this->dequeName, count($itemRepresentations) ? implode(' ', $itemRepresentations) : 'NONE');
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return $this->size();
    }
}
