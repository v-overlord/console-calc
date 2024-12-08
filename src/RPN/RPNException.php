<?php

namespace Calc\RPN;

use Calc\Lexer\Token as LexerToken;

class RPNException extends \Exception
{
    private string $messageFormat = "%s [raw: %s]";
    public LexerToken $token;

    public function __construct(string $message, LexerToken $token)
    {
        $this->token = $token;
        parent::__construct($message);
    }

    public function toString(): string
    {
        return sprintf($this->messageFormat, $this->message, $this->token->toString());
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}