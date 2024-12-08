<?php

namespace Calc\Command;

use Calc\Renderer\DataBag;
use Calc\Renderer\RendererFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    protected LoggerInterface $logger;

    protected RendererFactory $rendererFactory;

    protected ?InputInterface $input = null;

    protected ?OutputInterface $output = null;

    protected ?DataBag $dataToRender = null;

    public function __construct(LoggerInterface $logger, RendererFactory $rendererFactory)
    {
        $this->logger = $logger;
        $this->rendererFactory = $rendererFactory;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $result = $this->do();

        if ($this->dataToRender !== null) {
            $this->rendererFactory->getRenderer($this->input, $this->output)->render($this->dataToRender);
        }
        
        return $result;
    }

    abstract protected function do(): int;
}