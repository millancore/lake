<?php

namespace Lake\Console;

use RuntimeException;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Yaml\Yaml;

class Application extends BaseApplication
{
    const VERSION = '0.0.1';

    private $config;

    public function __construct(string $executePath)
    {
        parent::__construct('Lake, TDD Code Mirror', self::VERSION);
        $this->loadConfig($executePath);
        $this->registerCommands();
    }

    private function loadConfig($executePath)
    {
        $configFile = $executePath.DS.'lake.yml';

        if (!file_exists($configFile)) {
            throw new RuntimeException('Unable to locate lake.yml file.');
        }

        $this->config = Yaml::parseFile($configFile);
    }

    private function registerCommands()
    {
        $this->add(new \Lake\Command\MakeCommand($this->config));
        $this->add(new \Lake\Command\DumpCommand);
    }

}
