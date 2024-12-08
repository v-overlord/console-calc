<?php

namespace Calc\RPN\Token;

use Calc\RPN\SolveException;

class FunctionToken extends AbstractToken
{
    private array $functionsData;
    protected string $tokenName = 'FUNCTION';
    private string $functionName;

    private int $requiredArguments;

    public function __construct(string $functionName)
    {
        $this->fillUpFunctions();
        $this->handleFunctionName($functionName);
        $this->setUpRequiredArguments();
    }

    /**
     * @param array<NumberToken> $arguments
     * @return NumberToken
     */
    public function solve(array $arguments): NumberToken
    {
        $args = [];

        foreach ($arguments as $argument) {
            $args[] = $argument->getValue();
        }

        return new NumberToken($this->functionsData[$this->functionName]['function']($args));
    }

    /**
     * @throws SolveException
     */
    private function handleFunctionName(string $functionName): void
    {
        $this->functionName = trim(mb_strtolower($functionName));

        if (!isset($this->functionsData[$this->functionName])) {
            throw new SolveException('Function "' . $this->functionName . '" does not exist.');
        }
    }

    private function fillUpFunctions(): void
    {
        $this->functionsData = [
            'pow' => [
                'requiredArguments' => 2,
                'function' => fn($arguments) => pow($arguments[0], $arguments[1])
            ],
            'cos' => [
                'requiredArguments' => 1,
                'function' => fn($arguments) => cos($arguments[0])
            ],
            'sin' => [
                'requiredArguments' => 1,
                'function' => fn($arguments) => sin($arguments[0])
            ],
        ];
    }

    private function setUpRequiredArguments(): void
    {
        $this->requiredArguments = $this->functionsData[$this->functionName]['requiredArguments'];
    }

    public function getRequiredArguments(): int
    {
        return $this->requiredArguments;
    }

    public function getRepresentation(): string
    {
        return $this->functionName;
    }
}