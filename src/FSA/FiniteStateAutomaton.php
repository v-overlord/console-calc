<?php

namespace Calc\FSA;

class FiniteStateAutomaton
{
    private int $nextState;

    public function __construct(
        /**
         * @var array<int>
         */
        private readonly array $states,

        /**
         * @var array<int, array<int>>
         */
        private readonly array $transitions,
        /**
         * @var int
         */
        private int            $currentState,
    )
    {
        $this->nextState = $this->currentState;
    }

    public function transit(int $nextState): ?int
    {
        if ($this->isTransitionAllowed($nextState)) {
            $this->nextState = $nextState;

            return $this->nextState;
        }

        return null;
    }

    public function tick(): void
    {
        if ($this->nextState !== $this->currentState) {
            $this->currentState = $this->nextState;
            $this->nextState = $this->currentState;
        }
    }

    function getCurrentState(): ?int
    {
        return $this->currentState;
    }

    private function isTransitionAllowed(int $state): bool
    {
        return isset($this->transitions[$this->currentState]) && in_array($state, $this->transitions[$this->currentState], true) && in_array($state, $this->states, true);
    }
}