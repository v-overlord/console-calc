<?php

namespace Calc\RPN\Token;

use Calc\RPN\SolveException;

class OperatorToken extends AbstractToken
{
    protected string $tokenName = 'OPERATOR';

    private int $precedence;

    private int $requiredArguments;

    public function __construct(
        private readonly string $value,
    )
    {
        $this->determineConfig();
    }

    /**
     * @param array<NumberToken> $arguments
     * @return NumberToken
     * @throws SolveException
     */
    public function solve(array $arguments): NumberToken
    {
        return new NumberToken(match ($this->value) {
            '+' => $arguments[0]->getValue() + $arguments[1]->getValue(),
            '-' => $arguments[0]->getValue() - $arguments[1]->getValue(),
            '*' => $arguments[0]->getValue() * $arguments[1]->getValue(),
            '/' => $arguments[0]->getValue() / $arguments[1]->getValue(),
            '~' => -$arguments[0]->getValue(),
            // @codeCoverageIgnoreStart
            default => throw new SolveException(sprintf("Can't solve operator '%s': %s", $this->toString(), implode(',', $arguments))),
            // @codeCoverageIgnoreEnd
        });
    }

    private function determineConfig(): void
    {
        [$this->precedence, $this->requiredArguments] = match ($this->value) {
            '~' => [6, 1],
            '*' => [4, 2],
            '/' => [3, 2],
            '-' => [2, 2],
            '+' => [1, 2],
            default => throw new \InvalidArgumentException("Invalid operator '{$this->value}'"),
        };
    }

    public function getRepresentation(): string
    {
        return $this->value;
    }

    public function getRequiredArguments(): int
    {
        return $this->requiredArguments;
    }

    public function getPrecedence(): int
    {
        return $this->precedence;
    }
}