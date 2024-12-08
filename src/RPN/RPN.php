<?php

namespace Calc\RPN;

use Calc\Lexer\TokenStream as LexerTokenStream;
use Calc\RPN\Token\AbstractToken;
use Calc\RPN\Token\BracketToken;
use Calc\RPN\Token\CommaToken;
use Calc\RPN\Token\ConstToken;
use Calc\RPN\Token\FunctionToken;
use Calc\RPN\Token\NumberToken;
use Calc\RPN\Token\OperatorToken;

class RPN
{
    private TokenStream $tokenStream;

    private Deque $holdingDeque;

    private Deque $outputDeque;

    private Deque $solveDeque;

    public function __construct(
        private readonly LexerTokenStream $lexerTokenStream
    )
    {
        $this->reset();
        $this->tokenStream = (new TokenSpecializer($this->lexerTokenStream))->specializeRawTokens();
    }

    /**
     * @throws SolveException
     */
    public function solve(): float
    {
        $this->sortOutTokensToRPNForm();

        while ($this->outputDeque->size() > 0) {
            $token = $this->outputDeque->removeFront();

            if ($token instanceof NumberToken) {
                $this->solveDeque->addBack($token);
            } elseif ($token instanceof OperatorToken) {
                $this->executeOperator($token);
            } elseif ($token instanceof FunctionToken) {
                $this->executeFunction($token);
            } elseif ($token instanceof ConstToken) {
                $this->executeConst($token);
            }
        }

        if ($this->solveDeque->size() > 1) {
            throw new SolveException("Cannot solve the result: malformed expression");
        }

        if (!($this->solveDeque->peekBack() instanceof NumberToken)) {
            // @codeCoverageIgnoreStart
            throw new SolveException("Cannot solve the result: the result isn't a number");
            // @codeCoverageIgnoreEnd
        }

        /**
         * @var NumberToken $result
         */
        $result = $this->solveDeque->peekBack();

        return $result->getValue();
    }

    /**
     * @throws SolveException
     */
    private function executeConst(ConstToken $token): void
    {
        $this->solveDeque->addBack($token->solve());
    }

    /**
     * @throws SolveException
     */
    private function executeFunction(FunctionToken $token): void
    {
        $arguments = $this->getArguments($token);
        $result = $token->solve($arguments);

        $this->solveDeque->addBack($result);
    }

    /**
     * @throws SolveException
     */
    private function executeOperator(OperatorToken $token): void
    {
        $arguments = $this->getArguments($token);
        $result = $token->solve($arguments);

        $this->solveDeque->addBack($result);
    }

    /**
     * @param OperatorToken|FunctionToken $token
     * @return array<NumberToken>
     * @throws SolveException
     */
    private function getArguments(OperatorToken|FunctionToken $token): array
    {
        $requiredArgumentsNumber = $token->getRequiredArguments();

        if ($this->solveDeque->size() < $requiredArgumentsNumber) {
            throw new SolveException("Cannot execute '$token': it requires '$requiredArgumentsNumber' arguments, but the solve stack contains too few.");
        }

        $arguments = [];
        for ($i = 0; $i < $requiredArgumentsNumber; $i++) {
            $possibleNumber = $this->solveDeque->removeBack();

            if (!($possibleNumber instanceof NumberToken)) {
                // @codeCoverageIgnoreStart
                throw new SolveException("Cannot execute the operator '$token': it expects numeric arguments but received: $possibleNumber.");
                // @codeCoverageIgnoreEnd
            }

            $arguments[] = $possibleNumber;
        }

        return array_reverse($arguments);
    }

    /**
     * The shunting yard algorithm, O(n)
     *
     * @return void
     */
    private function sortOutTokensToRPNForm(): void
    {
        foreach ($this->tokenStream->stream() as $token) {
            if (($token instanceof NumberToken) || ($token instanceof ConstToken)) {
                $this->outputDeque->addBack($token);
            } elseif ($token instanceof OperatorToken) {
                $this->drainTill($token, fn(AbstractToken $operatorOnTop) => $operatorOnTop instanceof OperatorToken && $operatorOnTop->getPrecedence() >= $token->getPrecedence());

                $this->holdingDeque->addBack($token);
            } elseif ($token instanceof FunctionToken) {
                $this->holdingDeque->addBack($token);
            } elseif ($token instanceof BracketToken) {
                if ($token->isOpenBracket()) {
                    $this->holdingDeque->addBack($token);
                } else {
                    $this->drainTill($token, fn(AbstractToken $token) => !($token instanceof BracketToken) || !$token->isOpenBracket());
                    $this->holdingDeque->removeBack();

                    $tokenOnTop = $this->holdingDeque->peekBack();

                    if ($tokenOnTop instanceof FunctionToken) {
                        $this->outputDeque->addBack($tokenOnTop);
                        $this->holdingDeque->removeBack();
                    }
                }
            } elseif ($token instanceof CommaToken) {
                $this->drainTill($token, fn(AbstractToken $token) => !($token instanceof BracketToken) && !$token->isOpenBracket());
            }
        }

        while ($this->holdingDeque->size() > 0) {
            $this->outputDeque->addBack($this->holdingDeque->removeBack());
        }
    }

    /**
     * @param AbstractToken $token
     * @param callable(AbstractToken): bool $conditionCallback
     *
     * @return void
     */
    private function drainTill(AbstractToken $token, callable $conditionCallback): void
    {
        if ($this->holdingDeque->size() > 0) {
            while ($this->holdingDeque->size() > 0) {
                $tokenOnTop = $this->holdingDeque->peekBack();

                if ($conditionCallback($tokenOnTop)) {
                    $higherPrecedenceOperator = $this->holdingDeque->removeBack();
                    $this->outputDeque->addBack($higherPrecedenceOperator);
                } else {
                    break;
                }
            }
        }
    }

    private function reset(): void
    {
        $this->holdingDeque = new Deque("Holding");
        $this->outputDeque = new Deque("Output");
        $this->solveDeque = new Deque("Solve");
    }
}