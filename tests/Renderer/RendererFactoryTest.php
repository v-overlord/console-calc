<?php

declare(strict_types=1);

namespace tests\Command\Renderer;

use Calc\Renderer\CliRenderer;
use Calc\Renderer\RendererFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[CoversClass(RendererFactory::class)]
class RendererFactoryTest extends TestCase
{
    public function testHappyPath(): void
    {
        $rendererFactory = new RendererFactory('cli');

        $input_mock = $this->createMock(InputInterface::class);
        $output_mock = $this->createMock(OutputInterface::class);

        $this->assertTrue($rendererFactory->getRenderer($input_mock, $output_mock) instanceof CliRenderer);
    }

    public function testIsUnknownRendererIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $rendererFactory = new RendererFactory('__unknown__');

        $input_mock = $this->createMock(InputInterface::class);
        $output_mock = $this->createMock(OutputInterface::class);

        $rendererFactory->getRenderer($input_mock, $output_mock);
    }
}