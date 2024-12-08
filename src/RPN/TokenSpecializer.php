<?php

namespace Calc\RPN;

use Calc\Lexer\Token as LexerToken;
use Calc\Lexer\TokenStream as LexerTokenStream;
use Calc\Lexer\TokenType as LexerTokenType;
use Calc\RPN\Token\AbstractToken;
use Calc\RPN\Token\BracketToken;
use Calc\RPN\Token\CommaToken;
use Calc\RPN\Token\ConstToken;
use Calc\RPN\Token\FunctionToken;
use Calc\RPN\Token\NumberToken;
use Calc\RPN\Token\OperatorToken;

class TokenSpecializer
{
    private TokenStream $tokenStream;

    private ?AbstractToken $previousToken;

    public function __construct(
        /**
         * @var LexerTokenStream
         */
        private readonly LexerTokenStream $lexerTokenStream,
    )
    {
        $this->tokenStream = new TokenStream([]);
        $this->previousToken = null;
    }

    /**
     * @throws RPNException
     */
    public function specializeRawTokens(): TokenStream
    {
        foreach ($this->lexerTokenStream->stream() as $rawToken) {
            // That's dirty, don't do that, handling things at a more abstract level than necessary is a terrible idea.
            if ($this->previousToken instanceof ConstToken && $rawToken->getTokenType() !== LexerTokenType::BRACKET) {
                $this->tokenStream->pushToken($this->previousToken);
            }

            $newToken = match ($rawToken->getTokenType()) {
                LexerTokenType::NUMERIC => $this->specializeNumber($rawToken),
                LexerTokenType::OPERATOR => $this->specializeOperator($rawToken),
                LexerTokenType::STRING => $this->specializeString($rawToken),
                LexerTokenType::COMMA => $this->specializeComma($rawToken),
                LexerTokenType::BRACKET => $this->specializeBracket($rawToken),
                // @codeCoverageIgnoreStart
                default => throw new RPNException("Got unknown token", $rawToken),
                // @codeCoverageIgnoreEnd
            };

            $this->previousToken = $newToken;
        }

        if ($this->previousToken instanceof ConstToken) {
            $this->tokenStream->pushToken($this->previousToken);
        }

        return $this->tokenStream;
    }

    private function specializeNumber(LexerToken $token): ?AbstractToken
    {
        $number = new NumberToken(
            floatval($token->getTokenValue()),
        );

        $this->tokenStream->pushToken($number);

        return $number;
    }

    /**
     * @throws RPNException
     */
    private function specializeString(LexerToken $token): ?AbstractToken
    {
        $constToken = new ConstToken($token->getTokenValue());

        if (!$constToken->isValid()) {
            throw new RPNException("Invalid constant/function name: [$constToken]", $token);
        }

        return $constToken;
    }

    private function specializeComma(LexerToken $token): ?AbstractToken
    {
        $comma = new CommaToken($token->getTokenValue());

        $this->tokenStream->pushToken($comma);

        return $comma;
    }

    private function specializeBracket(LexerToken $token): ?AbstractToken
    {
        $bracket = new BracketToken($token->getTokenValue());

        if ($this->previousToken instanceof ConstToken) {
            if ($bracket->canBeCallBracket()) {
                $functionToken = new FunctionToken($this->previousToken->getConstName());

                $this->tokenStream->pushToken($functionToken);
            } else {
                $this->tokenStream->pushToken($this->previousToken);
            }
        }

        $this->tokenStream->pushToken($bracket);

        return $bracket;
    }

    private function specializeOperator(LexerToken $token): ?AbstractToken
    {
        $operator = new OperatorToken($token->getTokenValue());

        $this->tokenStream->pushToken($operator);

        return $operator;
    }
}