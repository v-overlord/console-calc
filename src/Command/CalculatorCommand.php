<?php

namespace Calc\Command;

use Calc\Lexer\Exception as LexerException;
use Calc\Lexer\Lexer;
use Calc\Renderer\DataBag;
use Calc\RPN\RPN;
use Calc\RPN\RPNException;
use Calc\RPN\SolveException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'calc', description: 'This calculates your math expression')]
class CalculatorCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setDescription('This calculates your math expression.')
            ->addArgument('expression', InputArgument::REQUIRED, 'Your expression');
    }


    protected function do(): int
    {
        $commandResult = Command::SUCCESS;


        $rawInput = $this->input->getArgument('expression');

        try {
            $lexerTokenStream = (new Lexer($rawInput))->parseAndGetTokenStream();

            $rpn = new RPN($lexerTokenStream);
            $result = $rpn->solve();

            $this->dataToRender = new DataBag($result);
        } catch (LexerException $lexerException) {
            $this->logger->error("LEXER ERROR: " . $lexerException->toString());
            $commandResult = Command::FAILURE;
        } catch (SolveException $solveException) {
            $this->logger->error("SOLVE ERROR: " . $solveException->getMessage());
            $commandResult = Command::FAILURE;
        } catch (RPNException $RPNException) {
            $this->logger->error("RPN ERROR: " . $RPNException->toString());
            $commandResult = Command::FAILURE;
        }

        return $commandResult;
    }
}
