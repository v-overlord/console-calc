<?php

namespace Calc\Renderer;

class DataBag
{
    public string $data = '';

    public int $flags = 0;

    /**
     * @param string $data
     * @param int $flags
     */
    public function __construct(string $data, int $flags = 0)
    {
        $this->data = $data;
        $this->flags = $flags;
    }
}