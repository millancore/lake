<?php

namespace Lake\Console;

use Lake\Config;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Yaml\Yaml;

class Application extends BaseApplication
{
    const VERSION = '0.0.1';

    private $config;

    public function __construct(string $executePath)
    {
        parent::__construct('Lake, Code Mirror', self::VERSION);
        $this->config = new Config($this->loadFromConfigFile($executePath));
        $this->registerCommands();
    }

    private function loadFromConfigFile($executePath)
    {
        $configFile = $executePath.DS.'lake.yml';

        $optionsFromFile = [];
        if (file_exists($configFile)) {
           $optionsFromFile = Yaml::parseFile($configFile);
        }
        
        return $optionsFromFile; 
    }


    private function registerCommands()
    {
        $this->add(new \Lake\Command\MakeCommand($this->config));
        $this->add(new \Lake\Command\DumpCommand);
    }

}
