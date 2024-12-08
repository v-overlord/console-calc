<?php

namespace Calc\RPN\Token;

use Calc\RPN\SolveException;

class ConstToken extends AbstractToken
{
    private const array ALLOWED_CONSTANTS = [
        'pi' => M_PI,
        'e' => M_E
    ];

    protected string $tokenName = 'CONST';
    private string $constName;

    /**
     * @throws SolveException
     */
    public function __construct(string $constName)
    {
        $this->handleConstName($constName);
    }

    /**
     * @return NumberToken
     * @throws SolveException
     */
    public function solve(): NumberToken
    {
        if (!isset(self::ALLOWED_CONSTANTS[$this->constName])) {
            throw new SolveException('Constant "' . $this->constName . '" does not exist.');
        }
        return new NumberToken(self::ALLOWED_CONSTANTS[$this->constName]);
    }

    private function handleConstName(string $constName): void
    {
        $this->constName = trim(mb_strtolower($constName));
    }

    public function isValid(): bool
    {
        // Just a basic example for clarity: a const or function name cannot start with '-'
        return $this->constName[0] !== '-';
    }

    public function getRepresentation(): string
    {
        return $this->constName;
    }

    public function getConstName(): string
    {
        return $this->constName;
    }
}