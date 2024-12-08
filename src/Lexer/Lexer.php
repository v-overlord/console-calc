<?php

namespace Calc\Lexer;

use Calc\FSA\FiniteStateAutomaton;
use Calc\Utils\LookupTable;


class Lexer
{
    private Token $currentToken;

    private TokenStream $tokenStream;

    private ?string $currentChar;

    private FiniteStateAutomaton $finiteStateAutomaton;
    private LexerInputStream $inputStream;

    // In this case, it's much simpler than other implementation methods
    private LookupTable $numbersLookUpTable;
    private LookupTable $operatorsLookUpTable;
    private LookupTable $bracketsLookUpTable;
    private LookupTable $commaLookUpTable;

    private LookupTable $skipCharsLookupTable;
    private LookupTable $stringsLookUpTable;

    public function __construct(
        /**
         * @var string
         */
        private readonly string $rawInput
    )
    {
        $this->resetAll();
    }

    /**
     * @throws Exception
     */
    public function parseAndGetTokenStream(): TokenStream
    {
        while (!$this->inputStream->isEOF()) {
            $this->currentChar = $this->inputStream->current();

            switch ($this->finiteStateAutomaton->getCurrentState()) {
                case LexerStates::NEW->value:
                    $this->handlerNew();
                    break;
                case LexerStates::NUMBER->value:
                    $this->handlerNumber();
                    break;
                case LexerStates::OPERATOR->value:
                    $this->handlerOperator();
                    break;
                case LexerStates::STRING->value:
                    $this->handlerString();
                    break;
                case LexerStates::BRACKET->value:
                    $this->handlerBracket();
                    break;
                case LexerStates::COMMA->value:
                    $this->handlerComma();
                    break;
                case LexerStates::COMPLETE->value:
                    $this->handlerComplete();
                    break;
                default:
                    // @codeCoverageIgnoreStart
                    throw new Exception("Lexer: Got state [{$this->finiteStateAutomaton->getCurrentState()?->name}], but the handler for it does not exist.", $this->inputStream->getCurrentPosition());
                    // @codeCoverageIgnoreEnd
            }

            $this->finiteStateAutomaton->tick();
        }

        if ($this->finiteStateAutomaton->getCurrentState() !== LexerStates::COMPLETE->value || $this->currentToken->getTokenType() !== TokenType::UNKNOWN) {
            $this->finiteStateAutomaton->tick();
            $this->handlerComplete();
        }

        return $this->tokenStream;
    }

    /**
     * @throws Exception
     */
    private function handlerNew(): void
    {
        $char = $this->inputStream->current();
        $this->currentToken = Token::unspecified();

        if ($this->skipCharsLookupTable->check($char)) {
            $this->inputStream->next();
            return;
        }

        if ($this->numbersLookUpTable->check($char)) {
            $this->currentToken->setType(TokenType::NUMERIC);
            $this->finiteStateAutomaton->transit(LexerStates::NUMBER->value);
            return;
        }

        if ($this->operatorsLookUpTable->check($char)) {
            $this->currentToken->setType(TokenType::OPERATOR);
            $this->finiteStateAutomaton->transit(LexerStates::OPERATOR->value);
            return;
        }

        if ($this->stringsLookUpTable->check($char)) {
            $this->currentToken->setType(TokenType::STRING);
            $this->finiteStateAutomaton->transit(LexerStates::STRING->value);
            return;
        }

        if ($this->bracketsLookUpTable->check($char)) {
            $this->currentToken->setType(TokenType::BRACKET);
            $this->finiteStateAutomaton->transit(LexerStates::BRACKET->value);
            return;
        }

        if ($this->commaLookUpTable->check($char)) {
            $this->currentToken->setType(TokenType::COMMA);
            $this->finiteStateAutomaton->transit(LexerStates::COMMA->value);
            return;
        }

        throw new Exception("Unknown char '$char'", $this->inputStream->getCurrentPosition() + 1);
    }

    private function handlerNumber(): void
    {
        if ($this->numbersLookUpTable->check($this->currentChar)) {
            $this->currentToken->addToValue($this->currentChar);
            $this->inputStream->next();
        } else {
            $this->finiteStateAutomaton->transit(LexerStates::COMPLETE->value);
        }
    }

    private function handlerString(): void
    {
        if ($this->stringsLookUpTable->check($this->currentChar)) {
            $this->currentToken->addToValue($this->currentChar);
            $this->inputStream->next();
        } else {
            $this->finiteStateAutomaton->transit(LexerStates::COMPLETE->value);
        }
    }

    private function handlerOperator(): void
    {
        // Currently, an operator can only be a single character, so it doesn't support more complex operators like "<<" yet.
        if ($this->operatorsLookUpTable->check($this->currentChar)) {
            $this->currentToken->addToValue($this->currentChar);
            $this->inputStream->next();
            $this->finiteStateAutomaton->transit(LexerStates::COMPLETE->value);
        }
    }

    private function handlerBracket(): void
    {
        if ($this->bracketsLookUpTable->check($this->currentChar)) {
            $this->currentToken->addToValue($this->currentChar);
            $this->inputStream->next();
            $this->finiteStateAutomaton->transit(LexerStates::COMPLETE->value);
        }
    }

    private function handlerComma(): void
    {
        if ($this->commaLookUpTable->check($this->currentChar)) {
            $this->currentToken->addToValue($this->currentChar);
            $this->inputStream->next();
            $this->finiteStateAutomaton->transit(LexerStates::COMPLETE->value);
        }
    }

    private function handlerComplete(): void
    {
        $this->tokenStream->pushToken($this->currentToken);
        $this->finiteStateAutomaton->transit(LexerStates::NEW->value);
    }

    private function getTransitions(): array
    {
        return [
            LexerStates::NEW->value => [
                LexerStates::NUMBER->value,
                LexerStates::STRING->value,
                LexerStates::OPERATOR->value,
                LexerStates::BRACKET->value,
                LexerStates::COMMA->value,
                LexerStates::COMPLETE->value,
            ],
            LexerStates::NUMBER->value => [
                LexerStates::COMPLETE->value
            ],
            LexerStates::STRING->value => [
                LexerStates::COMPLETE->value
            ],
            LexerStates::OPERATOR->value => [
                LexerStates::COMPLETE->value
            ],
            LexerStates::BRACKET->value => [
                LexerStates::COMPLETE->value
            ],
            LexerStates::COMMA->value => [
                LexerStates::COMPLETE->value
            ],
            LexerStates::COMPLETE->value => [
                LexerStates::NEW->value
            ]
        ];
    }

    private function resetAll(): void
    {
        $this->currentToken = Token::unspecified();
        $this->finiteStateAutomaton = new FiniteStateAutomaton(LexerStates::values(), $this->getTransitions(), LexerStates::NEW->value);
        $this->inputStream = new LexerInputStream($this->rawInput);
        $this->tokenStream = new TokenStream();
        $this->currentChar = $this->inputStream->current();

        $this->numbersLookUpTable = new LookupTable("1234567890.");
        $this->operatorsLookUpTable = new LookupTable("/*-+~");
        $this->skipCharsLookupTable = new LookupTable(" \t\n");
        $this->bracketsLookUpTable = new LookupTable("{}()[]{}");
        $this->commaLookUpTable = new LookupTable(",");
        $this->stringsLookUpTable = new LookupTable("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-");
    }
}