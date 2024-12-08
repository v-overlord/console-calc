<?php

namespace Calc;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Kernel
{
    private ContainerBuilder $container;

    public function __construct()
    {
        $this->container = new ContainerBuilder();
        $this->loadConfiguration();
    }

    private function loadConfiguration(): void
    {
        $fileLocator = new FileLocator(__DIR__ . '/../config');

        $loader = new YamlFileLoader($this->container, $fileLocator);
        $loader->load('services.yaml');
        $this->container->compile();
    }

    public function getConsoleApplication(): Application
    {
        $application = new Application();

        // Register commands from the container
        $commands = $this->container->findTaggedServiceIds('console.command');
        foreach ($commands as $id => $tags) {
            $application->add($this->container->get($id));
        }

        return $application;
    }
}