<?php

namespace Calc\Renderer;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractRenderer
{
    public SymfonyStyle $io;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    abstract function render(DataBag $data): bool;
}