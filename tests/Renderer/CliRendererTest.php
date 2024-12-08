<?php

declare(strict_types=1);

namespace tests\Command\Renderer;

use Calc\Renderer\CliRenderer;
use Calc\Renderer\DataBag;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[CoversClass(CliRenderer::class)]
class CliRendererTest extends TestCase
{
    public function testHappyPath(): void
    {
        $textDataBag = new DataBag('test');

        $input_mock = $this->createMock(InputInterface::class);
        $output_mock = $this->createMock(OutputInterface::class);

        $cliRenderer = new CliRenderer($input_mock, $output_mock);

        $this->assertEquals('test', $cliRenderer->render($textDataBag));
    }
}