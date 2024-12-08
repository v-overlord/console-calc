<?php

namespace Calc\Renderer;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RendererFactory
{
    public function __construct(private string $rendererId) {}

    public function getRenderer(InputInterface $input, OutputInterface $output): AbstractRenderer
    {
        $format = mb_strtolower($this->rendererId);

        return match ($format) {
            'cli' => new CliRenderer($input, $output),
            default => throw new \InvalidArgumentException(sprintf("Can't find the renderer '%s'.", $format)),
        };
    }
}