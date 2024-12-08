<?php

namespace Calc\Renderer;

class CliRenderer extends AbstractRenderer
{
    function render(DataBag $data): bool
    {
        $this->io->writeln($data->data);

        return true;
    }
}